<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts
     */
    public function index(Request $request)
    {
        $query = Account::with(['agent', 'creator', 'updater', 'deleter']);

        // Apply filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
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

        // Default to current month if no date filter
        if (!$request->has('from_date') && !$request->has('to_date')) {
            $query->whereMonth('payment_date', Carbon::now()->month)
                ->whereYear('payment_date', Carbon::now()->year);
        }

        $accounts = $query->orderBy('payment_date', 'desc')->paginate(10);

        $agents = User::role('agent')->get();
        $editAccount = $request->has('edit') ? Account::find($request->edit) : null;

        return view('admin.accounts.index', compact(
            'accounts',
            'agents',
            'editAccount'
        ));
    }

    /**
     * Store or update account
     */
    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
            'notes' => 'nullable|string',

        ]);

        DB::beginTransaction();

        try {


            if ($request->filled('id')) {
                $account = Account::findOrFail($request->id);
            } else {
                $account = new Account();
            }

            $account->user_id = $request->user_id;
            $account->amount_paid = $request->amount_paid;
            $account->payment_method = $request->payment_method;
            $account->transaction_id = $request->transaction_id;
            $account->payment_date = $request->payment_date;
            $account->notes = $request->notes;
            $account->status = $request->status;
            $account->{$request->has('id') ? 'updated_by' : 'created_by'} = Auth::id();
            $account->save();

            DB::commit();

            return redirect()
                ->route('account.management')
                ->with('success', ($request->has('id') ? 'Updated' : 'Added') . ' payment successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('account.management')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific account
     */
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $account = Account::findOrFail($id);
            $account->deleted_by = Auth::id();
            $account->save();
            $account->delete();

            DB::commit();
            return redirect()
                ->route('account.management')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('account.management')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
