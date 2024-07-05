<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use App\Models\Policy;
use Illuminate\Support\Carbon;

class ClaimController extends Controller
{
    public function index(Request $request)
    {

        $agent_id = $request->input('agent_id', "");
        $date = $request->input('date', "");

        // Default date range (current month)
        $start_date = Carbon::now()->firstOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();

        // Adjust date range based on input
        if ($date == "year") {
            $start_date = Carbon::now()->startOfYear()->toDateString();
        } elseif (is_numeric($date)) {
            $month = intval($date);
            $start_date = Carbon::create(null, $month, 1)->toDateString();
            $end_date = Carbon::create(null, $month, 1)->endOfMonth()->toDateString();
        }

        // Query to fetch claims with optional filters
        $query = Claim::query();

        if (!empty($agent_id)) {
            $query->where('users_id', $agent_id);
        }

        $query->whereBetween('claim_date', [$start_date, $end_date]);

        $data = $query->get();
        return view('admin.claims.index', compact('data'));
    }

    public function create()
    {

        return view('admin.claims.create');
    }

    public function store(Request $request)
    {
        // Remove the early return statement
        // return $request;

        $request->validate([
            'policy_number' => 'required',
            'claim_number' => 'required',
            'users_id' => 'required',
            'claim_date' => 'required|date',
            'incident_date' => 'required|date',
            'amount_claimed' => 'nullable|numeric',
            'amount_approved' => 'nullable|numeric',
            'status' => 'required',
        ]);

        // Create a new claim instance
        $claim = new Claim();

        // Assign values from the request
        $claim->policy_number = $request->input('policy_number');
        $claim->claim_number = $request->input('claim_number');
        $claim->claim_date = $request->input('claim_date');
        $claim->customer_name = $request->input('customer_name');
        $claim->users_id = $request->input('users_id');
        $claim->incident_date = $request->input('incident_date');
        $claim->amount_claimed = $request->input('amount_claimed');
        $claim->amount_approved = $request->input('amount_approved');
        $claim->status = $request->input('status', 'Pending'); // Default status is 'Pending'


        if (!empty($request->policy_exist)) {
            $claim->policy_exist = 0;
        }

        // Save the claim
        $claim->save();

        // Redirect back with success message
        return redirect()->route('claims.index')->with('success', 'Claim added successfully.');
    }

    public function show(Claim $claim)
    {

        return view('claims.show', compact('claim'));
    }

    public function edit(Claim $claim)
    {
        // Check if the query parameter 'policy-number' is already present
        if ($claim->policy_exist == 0 && !request()->has('policy-number')) {
            // Redirect to the same route with the 'policy-number' parameter to prevent infinite loop
            return redirect()->route('claims.edit', ['claim' => $claim->id, 'policy-number' => 1]);
        }

        return view('admin.claims.edit', compact('claim'));
    }



    public function update(Request $request, Claim $claim)
    {

        $claim->update($request->all());

        return redirect()->route('claims.index')->with('success', 'Claim updated successfully.');
    }

    public function destroy(Claim $claim)
    {
        $claim->delete();

        return redirect()->route('claims.index')->with('success', 'Claim deleted successfully.');
    }
}
