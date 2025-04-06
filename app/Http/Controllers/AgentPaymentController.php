<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentPayment;
use App\Models\Policy;
use App\Models\Commission;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentPaymentController extends Controller
{
    /**
     * Display a listing of pending agent payments.
     */
    public function index()
    {
        $pendingPolicies = Policy::whereIn('payment_by', ['pay_later', 'pay_later_with_adjustment'])
            ->whereRaw('agent_amount_paid < agent_amount_due')
            ->with(['agentPayments', 'agent' => function($query) {
                return $query->select('id', 'commission_code', 'name');
            }])
            ->orderBy('policy_start_date', 'desc')
            ->paginate(20);
            
        return view('agent-payments.index', compact('pendingPolicies'));
    }

    /**
     * Show the form for creating a new agent payment.
     */
    public function create(Request $request)
    {
        $policy = Policy::findOrFail($request->policy_id);
        
        // Check if this policy has pending payments
        if (!in_array($policy->payment_by, ['pay_later', 'pay_later_with_adjustment']) || 
            $policy->agent_amount_paid >= $policy->agent_amount_due) {
            return redirect()->route('agent-payments.index')
                ->with('error', 'This policy does not have any pending payments.');
        }
        
        $agent = Commission::find($policy->agent_id);
        $paymentMethods = ['Cash', 'Bank Transfer', 'Check', 'Online Payment'];
        
        return view('agent-payments.create', compact('policy', 'agent', 'paymentMethods'));
    }

    /**
     * Store a newly created agent payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'policy_id' => 'required|exists:policies,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $policy = Policy::findOrFail($request->policy_id);
        
        // Check if payment amount is valid
        $remainingAmount = $policy->agent_amount_due - $policy->agent_amount_paid;
        if ($request->amount_paid > $remainingAmount) {
            return redirect()->back()
                ->with('error', 'Payment amount cannot exceed the remaining amount due (' . $remainingAmount . ').')
                ->withInput();
        }
        
        DB::beginTransaction();
        try {
            // Create the payment record
            $payment = new Transaction([
                'policy_id' => $policy->id,
                'agent_id' => $policy->agent_id,
                'amount_paid' => $request->amount_paid,
                'amount_remaining' => $remainingAmount - $request->amount_paid,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->transaction_id,
                'notes' => $request->notes,
                'payment_date' => $request->payment_date,
                'created_by' => Auth::id()
            ]);
            
            $payment->save();
            
            // Model observer will handle updating the policy
            
            DB::commit();
            
            return redirect()->route('agent-payments.index')
                ->with('success', 'Payment recorded successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error recording payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified agent payment.
     */
    public function show(Transaction $agentPayment)
    {
        $policy = $agentPayment->policy;
        $agent = $agentPayment->agent;
        
        return view('agent-payments.show', compact('agentPayment', 'policy', 'agent'));
    }

    /**
     * Show agent payment history for a specific policy
     */
    public function policyPayments($policyId)
    {
        $policy = Policy::findOrFail($policyId);
        $payments = Transaction::where('policy_id', $policyId)
            ->orderBy('payment_date', 'desc')
            ->get();
            
        return view('agent-payments.policy-payments', compact('policy', 'payments'));
    }

    /**
     * Show payment history for a specific agent
     */
    public function agentPayments($agentId)
    {
        $agent = Commission::findOrFail($agentId);
        $payments = Transaction::where('agent_id', $agentId)
            ->with('policy')
            ->orderBy('payment_date', 'desc')
            ->paginate(20);
            
        $pendingPolicies = Policy::where('agent_id', $agentId)
            ->whereIn('payment_by', ['pay_later', 'pay_later_with_adjustment'])
            ->whereRaw('agent_amount_paid < agent_amount_due')
            ->count();
            
        $totalPending = Policy::where('agent_id', $agentId)
            ->whereIn('payment_by', ['pay_later', 'pay_later_with_adjustment'])
            ->whereRaw('agent_amount_paid < agent_amount_due')
            ->sum(DB::raw('agent_amount_due - agent_amount_paid'));
            
        return view('agent-payments.agent-payments', compact('agent', 'payments', 'pendingPolicies', 'totalPending'));
    }

    /**
     * Generate a report of all pending payments
     */
    public function pendingReport(Request $request)
    {
        $query = Policy::whereIn('payment_by', ['pay_later', 'pay_later_with_adjustment'])
            ->whereRaw('agent_amount_paid < agent_amount_due')
            ->with('agent');
            
        if ($request->agent_id) {
            $query->where('agent_id', $request->agent_id);
        }
        
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('policy_start_date', [$request->start_date, $request->end_date]);
        }
        
        $pendingPolicies = $query->get();
        
        $agentTotals = $pendingPolicies->groupBy('agent_id')
            ->map(function ($policies) {
                return [
                    'agent_name' => $policies->first()->agent->name ?? 'Unknown',
                    'commission_code' => $policies->first()->agent->commission_code ?? 'Unknown',
                    'total_due' => $policies->sum(function ($policy) {
                        return $policy->agent_amount_due - $policy->agent_amount_paid;
                    }),
                    'policy_count' => $policies->count()
                ];
            });
            
        return view('agent-payments.pending-report', compact('pendingPolicies', 'agentTotals'));
    }
}