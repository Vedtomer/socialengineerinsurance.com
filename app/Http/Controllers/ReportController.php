<?php

namespace App\Http\Controllers;

use App\Models\CustomerPolicy;
use App\Models\Policy;
use App\Models\InsuranceCompany;
use App\Models\User;
use App\Models\InsuranceProduct;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the reports index page with filter options
     */
    public function index()
    {
        // Get data for filters
        $companies = InsuranceCompany::orderBy('name')->get();
        $agents = User::role('agent')->get();
        $customers = User::role('customer')->get();
        $insuranceProducts = InsuranceProduct::orderBy('name')->get();
        $paymentTypes = Policy::getPaymentTypes();
        $states = User::distinct('state')->whereNotNull('state')->pluck('state');
        $cities = User::distinct('city')->whereNotNull('city')->pluck('city');
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive'];
        
        return view('admin.reports.index', compact(
            'companies', 
            'agents', 
            'customers',
            'insuranceProducts', 
            'paymentTypes',
            'states',
            'cities',
            'statuses'
        ));
    }

    /**
     * Generate and download policy report
     */
    public function downloadPolicyReport(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
                'company_id' => 'nullable|exists:insurance_companies,id',
                'agent_id' => 'nullable|exists:users,id',
                'policy_type' => 'nullable|exists:insurance_products,id',
                'payment_by' => 'nullable|string',
            ]);

            // Start query
            $query = Policy::query();

            // Apply filters
            if ($request->filled('from_date')) {
                $query->whereDate('policy_start_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('policy_start_date', '<=', $request->to_date);
            }
            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }
            if ($request->filled('agent_id')) {
                $query->where('agent_id', $request->agent_id);
            }
            if ($request->filled('policy_type')) {
                $query->where('policy_type', $request->policy_type);
            }
            if ($request->filled('payment_by')) {
                $query->where('payment_by', $request->payment_by);
            }

            // Fetch records
            $policies = $query->with(['agent', 'company', 'insuranceProduct'])->get();

            // Handle no records
            if ($policies->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the selected filters.');
            }

            // Start Excel export
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $headers = [
                'A1' => 'Policy No',
                'B1' => 'Policy Start Date',
                'C1' => 'Policy End Date',
                'D1' => 'Customer Name',
                'E1' => 'Insurance Company',
                'F1' => 'Agent',
                'G1' => 'Policy Type',
                'H1' => 'Premium',
                'I1' => 'GST',
                'J1' => 'Agent Commission',
                'K1' => 'Net Amount',
                'L1' => 'Payment Type',
                'M1' => 'Discount',
                'N1' => 'Payout',
            ];
            foreach ($headers as $cell => $label) {
                $sheet->setCellValue($cell, $label);
            }

            // Fill data
            $row = 2;
            foreach ($policies as $policy) {
                $sheet->setCellValue('A' . $row, $policy->policy_no);
                $sheet->setCellValue('B' . $row, $policy->policy_start_date);
                $sheet->setCellValue('C' . $row, $policy->policy_end_date);
                $sheet->setCellValue('D' . $row, $policy->customername);
                $sheet->setCellValue('E' . $row, optional($policy->company)->name);
                $sheet->setCellValue('F' . $row, optional($policy->agent)->name);
                $sheet->setCellValue('G' . $row, optional($policy->insuranceProduct)->name);
                $sheet->setCellValue('H' . $row, $policy->premium);
                $sheet->setCellValue('I' . $row, $policy->gst);
                $sheet->setCellValue('J' . $row, $policy->agent_commission);
                $sheet->setCellValue('K' . $row, $policy->net_amount);
                $sheet->setCellValue('L' . $row, Policy::getPaymentTypes()[$policy->payment_by] ?? $policy->payment_by);
                $sheet->setCellValue('M' . $row, $policy->discount);
                $sheet->setCellValue('N' . $row, $policy->payout);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'N') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Save the file
            $filename = 'policy_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/public/reports/' . $filename);

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Policy report export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while generating the report: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download user report (for both agents and customers)
     */
    public function downloadUserReport(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
                'state' => 'nullable|string',
                'city' => 'nullable|string',
                'status' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
                'role' => 'required|in:agent,customer',
            ]);
    
            $role = $request->role;
            
            // Start query for users with the specified role
            $query = User::role($role);
            
            // Apply non-date filters to users
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }  
    
            if ($request->filled('user_id')) {
                $query->where('id', $request->user_id);
            }
    
            // Fetch all users with the specified role and filters
            $users = $query->get();
            
            // Filter users based on their policies' date range
            if ($request->filled('from_date') || $request->filled('to_date')) {
                $users = $users->filter(function ($user) use ($request, $role) {
                    // Query to check if user has policies within date range
                    if ($role === 'agent') {
                        $policyQuery = Policy::where('agent_id', $user->id);
                    } else { // customer
                        $policyQuery = CustomerPolicy::where('user_id', $user->id);
                    }
                    
                    // Apply date filters to policy_start_date
                    if ($request->filled('from_date')) {
                        $policyQuery->whereDate('policy_start_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $policyQuery->whereDate('policy_start_date', '<=', $request->to_date);
                    }
                    
                    // Return true if user has policies within date range
                    return $policyQuery->exists();
                });
            }
    
            // Handle no records
            if ($users->isEmpty()) {
                return redirect()->back()->with('error', "No {$role}s found for the selected filters.");
            }
    
            // Start Excel export
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
    
            // Set headers based on role
            if ($role === 'agent') {
                $headers = [
                    'A1' => 'Name',
                    'B1' => 'Email',
                    'C1' => 'State',
                    'D1' => 'City',
                    'E1' => 'Address',
                    'F1' => 'Mobile Number',
                    'G1' => 'PAN Number',
                    'H1' => 'Status',
                    'I1' => 'Date Joined',
                    'J1' => 'Commission Settlement Status',
                    'K1' => 'Total Policies',
                    'L1' => 'Total Commission',
                ];
            } else { // customer
                $headers = [
                    'A1' => 'Name',
                    'B1' => 'Email',
                    'C1' => 'State',
                    'D1' => 'City',
                    'E1' => 'Address',
                    'F1' => 'Mobile Number',
                    'G1' => 'Aadhar Number',
                    'H1' => 'PAN Number',
                    'I1' => 'Status',
                    'J1' => 'Date Joined',
                    'K1' => 'Total Policies',
                    'L1' => 'Total Premium',
                ];
            }
            
            foreach ($headers as $cell => $label) {
                $sheet->setCellValue($cell, $label);
            }
    
            // Fill data
            $row = 2;
            foreach ($users as $user) {
                $sheet->setCellValue('A' . $row, $user->name);
                $sheet->setCellValue('B' . $row, $user->email);
                $sheet->setCellValue('C' . $row, $user->state);
                $sheet->setCellValue('D' . $row, $user->city);
                $sheet->setCellValue('E' . $row, $user->address);
                $sheet->setCellValue('F' . $row, $user->mobile_number);
                
                if ($role === 'agent') {
                    // Get agent's policy count and commission with date filters
                    $policyQuery = Policy::where('agent_id', $user->id);
                    
                    // Apply date filters to policy query
                    if ($request->filled('from_date')) {
                        $policyQuery->whereDate('policy_start_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $policyQuery->whereDate('policy_start_date', '<=', $request->to_date);
                    }
                    
                    $policyCount = $policyQuery->count();
                    $totalCommission = $policyQuery->sum('agent_commission');
                    
                    $sheet->setCellValue('G' . $row, $user->pan_number);
                    $sheet->setCellValue('H' . $row, ucfirst($user->status));
                    $sheet->setCellValue('I' . $row, $user->created_at->format('Y-m-d'));
                    $sheet->setCellValue('J' . $row, $user->commission_settlement ? 'yes' : '-');
                    $sheet->setCellValue('K' . $row, $policyCount);
                    $sheet->setCellValue('L' . $row, $totalCommission);
                } else {
                    // Get customer's policy count and total premium with date filters
                    $policyQuery = CustomerPolicy::where('user_id', $user->id);
                    
                    // Apply date filters to policy query
                    if ($request->filled('from_date')) {
                        $policyQuery->whereDate('policy_start_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $policyQuery->whereDate('policy_start_date', '<=', $request->to_date);
                    }
                    
                    $policyCount = $policyQuery->count();
                    $totalPremium = $policyQuery->sum('premium');
                    
                    $sheet->setCellValue('G' . $row, $user->aadhar_number);
                    $sheet->setCellValue('H' . $row, $user->pan_number);
                    $sheet->setCellValue('I' . $row, ucfirst($user->status));
                    $sheet->setCellValue('J' . $row, $user->created_at->format('Y-m-d'));
                    $sheet->setCellValue('K' . $row, $policyCount);
                    $sheet->setCellValue('L' . $row, $totalPremium);
                }
                $row++;
            }
    
            // Auto-size columns
            foreach (range('A', 'L') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
    
            // Save the file
            $filename = $role . '_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/public/reports/' . $filename);
    
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }
    
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
    
            return response()->download($filePath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error($request->role . ' report export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while generating the report: ' . $e->getMessage());
        }
    }
}

