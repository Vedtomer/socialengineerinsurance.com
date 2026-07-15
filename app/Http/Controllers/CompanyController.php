<?php

namespace App\Http\Controllers;


use App\Models\InsuranceCompany;
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
        $companies = InsuranceCompany::all();
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
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:200', 
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();

        try {
            // Store the image in the storage/company folder
            $image->storeAs('public/company', $imageName);

            // Save company details to the database
            $company = new InsuranceCompany();
            $company->image = $imageName;
            $company->name = $request->name;
            $company->status = $request->status ?? 1; // assuming status is passed in the request
            $company->save();

            // Generate the slug
            $slug = $company->id . strtolower(substr($company->name, 0, 4));

            // Update the company with the generated slug
            $company->slug = $slug;
            $company->save();

            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        } catch (\Exception $e) {
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
    public function show(InsuranceCompany $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(InsuranceCompany $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InsuranceCompany $company)
{
 
    $request->validate([
        'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200', // Allow image to be optional during update
        'status' => 'required|in:0,1', // Validate status (0 or 1)
    ]);

    try {
        if ($request->hasFile('file')) {
            // Handle image upload if a new image is provided
            $image = $request->file('file');
            $imageName = time() . '.' . $image->extension();
            $image->storeAs('public/company', $imageName);
            $company->image = $imageName;
        }

        // Update other company details
        // $company->name = $request->name;
        $company->status = $request->status; // Set the status
        $company->save();

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    } catch (\Exception $e) {
        return $e;
        return redirect()->back()->with('error', 'Failed to update company.');
    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InsuranceCompany  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(InsuranceCompany $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function payout(Request $request, InsuranceCompany $company)
    {
        return view('admin.companies.payout', compact('company'));
    }

    public function payoutData(Request $request, InsuranceCompany $company)
    {
        $period = $request->get('period', 'annually');
        $financialYear = $request->get('financial_year'); 
        $percentage = (float)$request->get('percentage', 59);

        $years = explode('-', $financialYear);
        if (count($years) != 2) {
            return "Invalid Financial Year";
        }
        $startDate = $years[0] . '-04-01 00:00:00';
        $endDate = $years[1] . '-03-31 23:59:59';

        $query = \App\Models\Policy::where('company_id', $company->id)
            ->whereBetween('policy_start_date', [$startDate, $endDate]);

        if ($period == 'annually') {
            $totalNetAmount = $query->sum('net_amount');
            $totalPolicies = $query->count();
            $actualPayout = $query->sum('payout');
            $calculatedPayout = ($totalNetAmount * $percentage) / 100;
            return view('admin.companies.payout_result', [
                'totalNetAmount' => $totalNetAmount,
                'totalPolicies' => $totalPolicies,
                'actualPayout' => $actualPayout,
                'payout' => $calculatedPayout,
                'period' => $period
            ]);
        } else {
            $policies = $query->get();
            $monthlyData = [];
            for ($i = 4; $i <= 15; $i++) {
                $month = $i > 12 ? $i - 12 : $i;
                $year = $i > 12 ? $years[1] : $years[0];
                $monthlyData[sprintf("%04d-%02d", $year, $month)] = [
                    'net_amount' => 0,
                    'actual_payout' => 0,
                    'payout' => 0,
                    'policies' => 0,
                    'month_name' => date('M Y', strtotime(sprintf("%04d-%02d-01", $year, $month)))
                ];
            }

            foreach ($policies as $policy) {
                if (!$policy->policy_start_date) continue;
                $monthKey = date('Y-m', strtotime($policy->policy_start_date));
                if (isset($monthlyData[$monthKey])) {
                    $monthlyData[$monthKey]['policies'] += 1;
                    $monthlyData[$monthKey]['net_amount'] += (float)$policy->net_amount;
                    $monthlyData[$monthKey]['actual_payout'] += (float)$policy->payout;
                    $monthlyData[$monthKey]['payout'] += ((float)$policy->net_amount * $percentage) / 100;
                }
            }

            return view('admin.companies.payout_result', [
                'monthlyData' => $monthlyData,
                'period' => $period,
                'totalNetAmount' => array_sum(array_column($monthlyData, 'net_amount')),
                'totalActualPayout' => array_sum(array_column($monthlyData, 'actual_payout')),
                'totalPayout' => array_sum(array_column($monthlyData, 'payout')),
                'totalPolicies' => array_sum(array_column($monthlyData, 'policies'))
            ]);
        }
    }
}