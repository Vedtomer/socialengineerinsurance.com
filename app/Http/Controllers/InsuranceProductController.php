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
    // Validate the request input
    $request->validate([
        'name' => 'required',
    ]);

    // Initialize an empty variable for the image path
    $imagePath = null;

    // Check if the request has a file named 'icon'
    if ($request->hasFile('icon')) {
        // Get the uploaded file
        $image = $request->file('icon');
        // Create a unique filename based on the current timestamp and file extension
        $imageName = time() . '.' . $image->extension();
        // Move the uploaded file to the public/icon directory
        $image->move(public_path('icon'), $imageName);
        // Store the relative path of the image
        $imagePath = 'icon/' . $imageName;
    }

    // Create a new InsuranceProduct instance and fill it with the request data
    $insuranceProduct = new InsuranceProduct($request->all());
    // Set the icon field with the image path if an image was uploaded
    $insuranceProduct->icon = $imagePath;
    // Save the InsuranceProduct instance to the database
    $insuranceProduct->save();

    // Redirect to the insurance-products.index route with a success message
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
    // Validate the request input
    $request->validate([
        'name' => 'required',
    ]);

    // Check if the request has a file named 'icon'
    if ($request->hasFile('icon')) {
        // Get the uploaded file
        $image = $request->file('icon');
        // Create a unique filename based on the current timestamp and file extension
        $imageName = time() . '.' . $image->extension();
        // Move the uploaded file to the public/icon directory
        $image->move(public_path('icon'), $imageName);
        // Store the relative path of the new image
        $imagePath = 'icon/' . $imageName;

        // Delete the old image if it exists
        if ($insuranceProduct->icon) {
            $oldImagePath = public_path($insuranceProduct->icon);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Update the icon field with the new image path
        $insuranceProduct->icon = $imagePath;
    }

    // Update the InsuranceProduct instance with the request data
    $insuranceProduct->update($request->except('icon') + ['icon' => $insuranceProduct->icon]);

    // Redirect to the insurance-products.index route with a success message
    return redirect()->route('insurance-products.index')->with('success', 'Insurance product updated successfully.');
}

    public function destroy(InsuranceProduct $insuranceProduct)
    {
        $insuranceProduct->delete();
        return redirect()->route('insurance-products.index')->with('success', 'Insurance product deleted successfully.');
    }
}
