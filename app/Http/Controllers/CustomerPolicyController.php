<?php

namespace App\Http\Controllers;

use App\Models\CustomerPolicy;
use Illuminate\Http\Request;

class CustomerPolicyController extends Controller
{
    public function index()
    {
        $customerPolicies = CustomerPolicy::all();
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
        'policy_document' => 'nullable|mimes:pdf|max:2048', // PDF file, max size 2MB (optional and nullable)
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
