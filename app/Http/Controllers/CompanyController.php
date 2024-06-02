<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200', 
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();

            try {
                // Store the image in the storage/slider folder
                $image->storeAs('public/company', $imageName);

                // Save slider details to the database
                $slider = new Company();
                $slider->image = $imageName;
                $slider->name = $request->name;
                $slider->save();

                return redirect()->route('companies.index')->with('success', 'Company created successfully.');
            } catch (\Exception $e) {
                return $e;
                return redirect()->back()->with('error', 'Failed to upload image.');
            }
        }

        return redirect()->back()->with('error', 'No image uploaded.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|boolean',
        ]);

        $company->name = $request->name;
        if ($request->hasFile('image')) {
            $company->image = $request->file('image')->store('images', 'public');
        }
        $company->status = $request->status;
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}