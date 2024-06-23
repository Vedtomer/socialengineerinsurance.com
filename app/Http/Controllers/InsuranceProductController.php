<?php

namespace App\Http\Controllers;

use App\Models\InsuranceProduct;
use Illuminate\Http\Request;

class InsuranceProductController extends Controller
{
    public function index()
    {
        $insuranceProducts = InsuranceProduct::all();
        return view('admin.insurance_products.index', compact('insuranceProducts'));
    }

    public function create()
    {
        return view('admin.insurance_products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        InsuranceProduct::create($request->all());
        return redirect()->route('insurance-products.index')->with('success', 'Insurance product created successfully.');
    }

    public function show(InsuranceProduct $insuranceProduct)
    {
        return view('insurance_products.show', compact('insuranceProduct'));
    }

    public function edit(InsuranceProduct $insuranceProduct)
    {
        return view('admin.insurance_products.edit', compact('insuranceProduct'));
    }

    public function update(Request $request, InsuranceProduct $insuranceProduct)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $insuranceProduct->update($request->all());
        return redirect()->route('insurance-products.index')->with('success', 'Insurance product updated successfully.');
    }

    public function destroy(InsuranceProduct $insuranceProduct)
    {
        $insuranceProduct->delete();
        return redirect()->route('insurance-products.index')->with('success', 'Insurance product deleted successfully.');
    }
}
