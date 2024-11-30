<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = User::role('customer')->orderBy("id", "desc")->get();
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'required|string',
            'aadhar_document' => 'nullable|max:2048',
            'pan_document' => 'nullable|max:2048',
             'username' => 'required|string|unique:users,username|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file uploads if present
        $aadharFileName = null;
        $panFileName = null;

        if ($request->hasFile('aadhar_document')) {
            $aadharFile = $request->file('aadhar_document');
            $aadharFileName = $request->aadhar_number . '.' . $aadharFile->getClientOriginalExtension();
            $aadharFile->storeAs('public/aadhar', $aadharFileName);
        }

        if ($request->hasFile('pan_document')) {
            $panFile = $request->file('pan_document');
            $panFileName = $request->pan_number . '.' . $panFile->getClientOriginalExtension();
            $panFile->storeAs('public/pancard', $panFileName);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->mobile_number = $request->input('mobile_number');
        $user->aadhar_number = $request->input('aadhar_number');
        $user->pan_number = $request->input('pan_number');
        $user->state = $request->input('state');
        $user->city = $request->input('city');
        $user->address = $request->input('address');
        $user->aadhar_document = $aadharFileName;
        $user->pan_document = $panFileName;
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        // $user->password = Hash::make($request->input('pan_number'));
        $user->save();
        $user->assignRole('customer');
        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $customer)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'aadhar_document' => 'nullable|file|max:2048',
            'pan_document' => 'nullable|file|max:2048',
             'username' => 'required|max:255'
            // 'password' => 'nullable|string|min:8|confirmed', // Add password validation
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle file uploads if present
        $aadharFileName = $customer->aadhar_document; // Retain old file name if not updated
        $panFileName = $customer->pan_document; // Retain old file name if not updated

        if ($request->hasFile('aadhar_document')) {
            $aadharFile = $request->file('aadhar_document');
            $aadharFileName = $request->aadhar_number . '.' . $aadharFile->getClientOriginalExtension();
            $aadharFile->storeAs('public/aadhar', $aadharFileName);
        }

        if ($request->hasFile('pan_document')) {
            $panFile = $request->file('pan_document');
            $panFileName = $request->pan_number . '.' . $panFile->getClientOriginalExtension();
            $panFile->storeAs('public/pancard', $panFileName);
        }

        // Update customer details
        $customer->name = $request->input('name');
        $customer->mobile_number = $request->input('mobile_number');
        $customer->aadhar_number = $request->input('aadhar_number');
        $customer->pan_number = $request->input('pan_number');
        $customer->state = $request->input('state');
        $customer->city = $request->input('city');
        $customer->address = $request->input('address');
        $customer->aadhar_document = $aadharFileName;
        $customer->pan_document = $panFileName;
        $customer->username = $request->input('username');

        // Update password if provided
        if ($request->filled('password')) {
            $customer->password = Hash::make($request->input('password'));
        }

        $customer->save();

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function changePassword(Request $request, User $customer)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $customer->password = Hash::make($request->input('password'));
            $customer->save();

            return redirect()->route('customers.index')->with('success', 'Password updated successfully.');
        }

        return view('admin.customers.change-password', compact('customer'));
    }
}
