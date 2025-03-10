<?php

namespace App\Http\Controllers;

use App\Models\CustomerPolicy;
use Illuminate\Http\Request;

class CustomerPolicyController extends Controller
{
   public function index(Request $request)
{
    $customerPolicies = CustomerPolicy::with('customer'); // Assuming you have 'customer' relationship

    $statusFilter = $request->query('status');
    $expiryFilter = $request->query('expiry');

    if ($statusFilter) {
        $customerPolicies->where('status', $statusFilter); // Assuming 'status' is your policy status column
    }

    if ($expiryFilter === 'this_month') {
        $customerPolicies->whereMonth('policy_end_date', now()->month); // Filter for current month
    } elseif ($expiryFilter === 'next_7_days') {
        $customerPolicies->where('policy_end_date', '<=', now()->addDays(7))
                         ->where('policy_end_date', '>=', now()); // Filter for next 7 days
    }

    $customerPolicies = $customerPolicies->get(); // Get the filtered policies

    return view('admin.customers_policies.index', compact('customerPolicies'));
}

    public function create()
    {
        // Show form to create a new customer policy
        return view('admin.customers_policies.create');
    }

    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'policy_no' => 'required',
        'policy_start_date' => 'required|date',
        'policy_end_date' => 'required|date',
        'user_id' => 'required',
        'status' => 'required',
        'net_amount' => 'required|numeric',
        'gst' => 'required|numeric',
        'premium' => 'required|numeric',
        'insurance_company' => 'required',
        'policy_type' => 'required',
        'product_id' => 'required',
        'policy_document' => 'nullable|mimes:pdf|max:2048',
    ]);

    // Handle file upload for policy document
    if ($request->hasFile('policy_document')) {
        $panFile = $request->file('policy_document');
        $panFileName = $request->policy_no . '.' . $panFile->getClientOriginalExtension();
        $panFile->storeAs('public/customer_policies', $panFileName);
    }

    // Create new CustomerPolicy instance
    $customerPolicy = new CustomerPolicy();
    $customerPolicy->policy_no = $validatedData['policy_no'];
    $customerPolicy->policy_start_date = $validatedData['policy_start_date'];
    $customerPolicy->policy_end_date = $validatedData['policy_end_date'];
    $customerPolicy->user_id = $validatedData['user_id'];
    $customerPolicy->status = $validatedData['status'];
    $customerPolicy->net_amount = $validatedData['net_amount'];
    $customerPolicy->gst = $validatedData['gst'];
    $customerPolicy->premium = $validatedData['premium'];
    $customerPolicy->insurance_company = $validatedData['insurance_company'];
    $customerPolicy->policy_type = $validatedData['policy_type'];
    $customerPolicy->product_id = $validatedData['product_id'];



    // Save the CustomerPolicy record
    $customerPolicy->save();

    // Redirect back with success message
    return redirect()->route('customer-policies.index')->with('success', 'Customer policy created successfully');
}

    public function show($id)
    {
        // Fetch and show a specific customer policy
        $customerPolicy = CustomerPolicy::findOrFail($id);

        return view('customer_policies.show', compact('customerPolicy'));
    }

    public function edit($id)
    {
        // Show form to edit a specific customer policy
        $customerPolicy = CustomerPolicy::findOrFail($id);
        return view('admin.customers_policies.edit', compact('customerPolicy'));
    }

    public function update(Request $request, $id)
    {
        // Validate and update a specific customer policy
        $customerPolicy = CustomerPolicy::findOrFail($id);

        if ($request->hasFile('policy_document')) {
            $panFile = $request->file('policy_document');
            $panFileName = $customerPolicy->policy_no . '.' . $panFile->getClientOriginalExtension();
            $panFile->storeAs('public/customer_policies', $panFileName);
        }

        $validatedData = $request->validate([
            'policy_no' => 'required',
            'policy_start_date' => 'required|date',
            'policy_end_date' => 'required|date',
            'user_id' => 'required',
            'status' => 'required',
            'net_amount' => 'required|numeric',
            'gst' => 'required|numeric',
            'premium' => 'required|numeric',
            'insurance_company' => 'required',
            'policy_type' => 'required',
            'product_id' => 'required',
        ]);

        $customerPolicy->update($validatedData);

        return redirect()->route('customer-policies.index')->with('success', 'Customer policy updated successfully');
    }

    public function destroy($id)
    {
        // Delete a specific customer policy
        $customerPolicy = CustomerPolicy::findOrFail($id);
        $customerPolicy->delete();

        return redirect()->route('customer-policies.index')->with('success', 'Customer policy deleted successfully');
    }
}
