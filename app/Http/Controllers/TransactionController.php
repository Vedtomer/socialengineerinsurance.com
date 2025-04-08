<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['policies', 'agent', 'creator']);

        // Apply filters
        if ($request->has('agent_id') && $request->agent_id) {
            $query->where('agent_id', $request->agent_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('payment_date', 'desc')
            ->paginate(10);

        // Get all agents for filter dropdown
        $agents = $agents = User::role('agent')
            ->whereHas('policies', function ($query) {
                $query->select(DB::raw('agent_id, SUM(agent_amount_due) as total_due'))
                    ->groupBy('agent_id')
                    ->havingRaw('SUM(agent_amount_due) > 0');
            })
            ->get();

        return view('admin.transactions.index', compact('transactions', 'agents'));
    }

    /**
     * Show the form for creating a new transaction
     */
    public function create(Request $request)
    {
        $agents = $agents = User::role('agent')
            ->whereHas('policies', function ($query) {
                $query->select(DB::raw('agent_id, SUM(agent_amount_due) as total_due'))
                    ->groupBy('agent_id')
                    ->havingRaw('SUM(agent_amount_due) > 0');
            })
            ->get();
        return view('admin.transactions.add-edit', compact('agents'));
    }

    /**
     * Store a newly created transaction
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'status' => 'required|string',
            'allocation_data' => 'required|json'
        ]);

        // Parse allocation data
        $allocationData = json_decode($request->allocation_data, true);

        if (empty($allocationData)) {
            return redirect()->back()->with('error', 'No valid policy allocation found.');
        }

        DB::beginTransaction();

        try {
            // Create a single transaction record
            $transaction = new Transaction();
            $transaction->agent_id = $request->agent_id;
            $transaction->amount_paid = $request->amount_paid;
            $transaction->payment_method = $request->payment_method;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->payment_date = $request->payment_date;
            $transaction->notes = $request->notes;
           // $transaction->status = $request->status;
            $transaction->created_by = Auth::id();
            $transaction->save();

            // Process each allocation using the pivot table
            foreach ($allocationData as $allocation) {
                $policy = Policy::findOrFail($allocation['policy_id']);
                $amount = floatval($allocation['amount']);

                // Create pivot relationship with amount
                $transaction->policies()->attach($policy->id, ['amount' => $amount]);

                // Update policy payment amounts
                $policy->agent_amount_paid = $policy->agent_amount_paid + $amount;
                $policy->save();
            }

            DB::commit();
            return redirect()->route('admin.transaction')->with('success', 'Transaction added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing transaction: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a transaction
     */
    public function edit(Transaction $transaction)
    {
        $agents = User::role('agent')->get();
        return view('admin.transactions.add-edit', compact('transaction', 'agents'));
    }

    /**
     * Update the specified transaction
     */
    /**
     * Update the specified transaction
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Validate the request
        $request->validate([
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|string',
            'allocation_data' => 'nullable|json' // Allow updating allocations
        ]);

        DB::beginTransaction();

        try {
            // Update transaction metadata
            $transaction->payment_method = $request->payment_method;
            $transaction->transaction_id = $request->transaction_id;
            $transaction->payment_date = $request->payment_date;
            $transaction->notes = $request->notes;
           // $transaction->status = $request->status;
            $transaction->save();

            // Handle allocation updates if provided
            if ($request->has('allocation_data') && $request->allocation_data) {
                $allocationData = json_decode($request->allocation_data, true);

                // First, reverse the previous allocations
                foreach ($transaction->policies as $policy) {
                    $previousAmount = $policy->pivot->amount;
                    $policy->agent_amount_paid = max(0, $policy->agent_amount_paid - $previousAmount);
                    $policy->save();
                }

                // Clear existing policy relationships
                $transaction->policies()->detach();

                // Create new allocations
                foreach ($allocationData as $allocation) {
                    $policy = Policy::findOrFail($allocation['policy_id']);
                    $amount = floatval($allocation['amount']);

                    // Create pivot relationship with amount
                    $transaction->policies()->attach($policy->id, ['amount' => $amount]);

                    // Update policy payment amounts
                    $policy->agent_amount_paid = $policy->agent_amount_paid + $amount;
                    $policy->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.transaction')->with('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating transaction: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified transaction
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();

        try {
            // Adjust the paid amounts for all related policies
            foreach ($transaction->policies as $policy) {
                $allocatedAmount = $policy->pivot->amount;

                // Reduce the policy's paid amount by the allocated amount
                $policy->agent_amount_paid = max(0, $policy->agent_amount_paid - $allocatedAmount);
                $policy->save();
            }

            // Delete the transaction (this will also delete pivot table entries)
            $transaction->delete();

            DB::commit();
            return redirect()->route('admin.transaction')->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting transaction: ' . $e->getMessage());
        }
    }

    /**
     * Get policies for an agent with remaining amounts
     */
    public function getAgentPolicies(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id'
        ]);

        $policies = Policy::where('agent_id', $request->agent_id)
            ->whereRaw('agent_amount_due > agent_amount_paid')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'policies' => $policies
        ]);
    }
}
