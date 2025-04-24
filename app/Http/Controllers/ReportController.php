<?php

namespace App\Http\Controllers;

use App\Models\Account;
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

        // Get count data for dashboard
        $policyCounts = [
            'total' => Policy::count(),

        ];

        $userCounts = [
            'agents' => User::role('agent')->count(),
            'customers' => User::role('customer')->count()
        ];



        $productCounts = InsuranceProduct::count();
        $companyCounts = InsuranceCompany::count();

        return view('admin.reports.index', compact(
            'companies',
            'agents',
            'customers',
            'insuranceProducts',
            'paymentTypes',
            'states',
            'cities',
            'statuses',
            'policyCounts',
            'userCounts',
            'productCounts',
            'companyCounts'
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
            'report_period' => 'nullable|in:daily,monthly,yearly',
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

        // Start Excel export
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Check report period selection
        $reportPeriod = $request->input('report_period', 'daily');
        
        if ($reportPeriod === 'daily') {
            // Original detailed report - fetch all policies
            $policies = $query->with(['agent', 'company', 'insuranceProduct'])->get();
            
            // Handle no records
            if ($policies->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the selected filters.');
            }
            
            // Headers for daily report (original format)
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
            
        } elseif ($reportPeriod === 'monthly') {
            // Get date range for headers
            $fromDate = $request->filled('from_date') 
                ? Carbon::parse($request->from_date) 
                : Carbon::now()->subMonths(5);
            $toDate = $request->filled('to_date') 
                ? Carbon::parse($request->to_date) 
                : Carbon::now();
            
            // Create array of months for column headers
            $months = [];
            $currentDate = Carbon::parse($fromDate)->startOfMonth();
            $endDate = Carbon::parse($toDate)->endOfMonth();
            
            while ($currentDate->lte($endDate)) {
                $months[] = [
                    'year_month' => $currentDate->format('Y-m'),
                    'display' => $currentDate->format('Y M')
                ];
                $currentDate->addMonth();
            }
            
            // Get all agents with policies in the date range
            $agents = Policy::query()
                ->whereBetween('policy_start_date', [$fromDate, $toDate])
                ->select('agent_id')
                ->with('agent:id,name')
                ->groupBy('agent_id')
                ->get()
                ->pluck('agent');
                
            // If no agents found
            if ($agents->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the selected filters.');
            }
            
            // Set up headers - Agent in first column, then each month
            $sheet->setCellValue('A1', 'Agent');
            $sheet->getStyle('A1')->getFont()->setBold(true);
            
            // Set month headers
            $col = 'B';
            foreach ($months as $index => $month) {
                $sheet->setCellValue($col . '1', $month['display']);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }
            
            // Add a total column
            $sheet->setCellValue($col . '1', 'Total');
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $totalCol = $col;
            
            // Add last policy date column
            $col++;
            $sheet->setCellValue($col . '1', 'Days Since Last Policy');
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $lastPolicyCol = $col;
            
            // Fill data for each agent
            $row = 2;
            foreach ($agents as $agent) {
                if (!$agent) continue; // Skip if agent is null
                
                $sheet->setCellValue('A' . $row, $agent->name);
                
                // Calculate totals and add monthly data
                $totalPolicies = 0;
                $col = 'B';
                
                // Most recent policy date
                $mostRecentPolicy = null;
                
                foreach ($months as $month) {
                    // Get count of policies for this agent in this month
                    $monthStart = Carbon::createFromFormat('Y-m', $month['year_month'])->startOfMonth();
                    $monthEnd = Carbon::createFromFormat('Y-m', $month['year_month'])->endOfMonth();
                    
                    $policyCount = Policy::query()
                        ->where('agent_id', $agent->id)
                        ->whereBetween('policy_start_date', [$monthStart, $monthEnd])
                        ->count();
                    
                    // Get most recent policy date
                    $latestPolicy = Policy::query()
                        ->where('agent_id', $agent->id)
                        ->whereBetween('policy_start_date', [$monthStart, $monthEnd])
                        ->latest('policy_start_date')
                        ->first();
                        
                    if ($latestPolicy && ($mostRecentPolicy === null || 
                        Carbon::parse($latestPolicy->policy_start_date)->gt(Carbon::parse($mostRecentPolicy)))) {
                        $mostRecentPolicy = $latestPolicy->policy_start_date;
                    }
                    
                    // Set policy count in cell
                    $sheet->setCellValue($col . $row, $policyCount);
                    
                    // Apply styling - blue cell with white text for non-zero counts
                    if ($policyCount > 0) {
                        $sheet->getStyle($col . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('4169E1'); // Royal Blue
                        $sheet->getStyle($col . $row)->getFont()->getColor()
                            ->setRGB('FFFFFF'); // White text
                        $sheet->getStyle($col . $row)->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    
                    $totalPolicies += $policyCount;
                    $col++;
                }
                
                // Set total policies
                $sheet->setCellValue($totalCol . $row, $totalPolicies);
                $sheet->getStyle($totalCol . $row)->getFont()->setBold(true);
                
                // Calculate days since last policy
                if ($mostRecentPolicy) {
                    $daysSinceLastPolicy = Carbon::parse($mostRecentPolicy)->diffInDays(Carbon::now());
                    $sheet->setCellValue($lastPolicyCol . $row, $daysSinceLastPolicy);
                    
                    // Color code based on recency
                    if ($daysSinceLastPolicy <= 7) {
                        // Green for recent activity (last 7 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('4CAF50');
                    } else if ($daysSinceLastPolicy <= 30) {
                        // Yellow for moderate activity (last 30 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC107');
                    } else {
                        // Red for inactive (more than 30 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F44336');
                    }
                    
                    $sheet->getStyle($lastPolicyCol . $row)->getFont()->getColor()
                        ->setRGB('FFFFFF'); // White text
                    $sheet->getStyle($lastPolicyCol . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->setCellValue($lastPolicyCol . $row, 'N/A');
                }
                
                $row++;
            }
            
            // Auto-size columns
            $lastCol = $lastPolicyCol;
            foreach (range('A', $lastCol) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Add title and date range
            $sheet->insertNewRowBefore(1, 2);
            $title = 'Agent Policy Performance - Monthly Report';
            $dateRange = 'Period: ' . $fromDate->format('M Y') . ' to ' . $toDate->format('M Y');
            
            $sheet->setCellValue('A1', $title);
            $sheet->setCellValue('A2', $dateRange);
            
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->mergeCells('A1:' . $lastCol . '1');
            $sheet->getStyle('A1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
            $sheet->mergeCells('A2:' . $lastCol . '2');
            $sheet->getStyle('A2')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
        } else { // yearly
            // Get date range for headers
            $fromDate = $request->filled('from_date') 
                ? Carbon::parse($request->from_date) 
                : Carbon::now()->subYears(2)->startOfYear();
            $toDate = $request->filled('to_date') 
                ? Carbon::parse($request->to_date) 
                : Carbon::now();
            
            // Create array of years for column headers
            $years = [];
            $currentDate = Carbon::parse($fromDate)->startOfYear();
            $endDate = Carbon::parse($toDate)->endOfYear();
            
            while ($currentDate->lte($endDate)) {
                $years[] = [
                    'year' => $currentDate->format('Y'),
                    'display' => $currentDate->format('Y')
                ];
                $currentDate->addYear();
            }
            
            // Get all agents with policies in the date range
            $agents = Policy::query()
                ->whereBetween('policy_start_date', [$fromDate, $toDate])
                ->select('agent_id')
                ->with('agent:id,name')
                ->groupBy('agent_id')
                ->get()
                ->pluck('agent');
                
            // If no agents found
            if ($agents->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the selected filters.');
            }
            
            // Set up headers - Agent in first column, then each year
            $sheet->setCellValue('A1', 'Agent');
            $sheet->getStyle('A1')->getFont()->setBold(true);
            
            // Set year headers
            $col = 'B';
            foreach ($years as $index => $year) {
                $sheet->setCellValue($col . '1', $year['display']);
                $sheet->getStyle($col . '1')->getFont()->setBold(true);
                $col++;
            }
            
            // Add a total column
            $sheet->setCellValue($col . '1', 'Total');
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $totalCol = $col;
            
            // Add last policy date column
            $col++;
            $sheet->setCellValue($col . '1', 'Days Since Last Policy');
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $lastPolicyCol = $col;
            
            // Fill data for each agent
            $row = 2;
            foreach ($agents as $agent) {
                if (!$agent) continue; // Skip if agent is null
                
                $sheet->setCellValue('A' . $row, $agent->name);
                
                // Calculate totals and add yearly data
                $totalPolicies = 0;
                $col = 'B';
                
                // Most recent policy date
                $mostRecentPolicy = null;
                
                foreach ($years as $year) {
                    // Get count of policies for this agent in this year
                    $yearStart = Carbon::createFromFormat('Y', $year['year'])->startOfYear();
                    $yearEnd = Carbon::createFromFormat('Y', $year['year'])->endOfYear();
                    
                    $policyCount = Policy::query()
                        ->where('agent_id', $agent->id)
                        ->whereBetween('policy_start_date', [$yearStart, $yearEnd])
                        ->count();
                    
                    // Get most recent policy date
                    $latestPolicy = Policy::query()
                        ->where('agent_id', $agent->id)
                        ->whereBetween('policy_start_date', [$yearStart, $yearEnd])
                        ->latest('policy_start_date')
                        ->first();
                        
                    if ($latestPolicy && ($mostRecentPolicy === null || 
                        Carbon::parse($latestPolicy->policy_start_date)->gt(Carbon::parse($mostRecentPolicy)))) {
                        $mostRecentPolicy = $latestPolicy->policy_start_date;
                    }
                    
                    // Set policy count in cell
                    $sheet->setCellValue($col . $row, $policyCount);
                    
                    // Apply styling - blue cell with white text for non-zero counts
                    if ($policyCount > 0) {
                        $sheet->getStyle($col . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('4169E1'); // Royal Blue
                        $sheet->getStyle($col . $row)->getFont()->getColor()
                            ->setRGB('FFFFFF'); // White text
                        $sheet->getStyle($col . $row)->getAlignment()
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                    
                    $totalPolicies += $policyCount;
                    $col++;
                }
                
                // Set total policies
                $sheet->setCellValue($totalCol . $row, $totalPolicies);
                $sheet->getStyle($totalCol . $row)->getFont()->setBold(true);
                
                // Calculate days since last policy
                if ($mostRecentPolicy) {
                    $daysSinceLastPolicy = Carbon::parse($mostRecentPolicy)->diffInDays(Carbon::now());
                    $sheet->setCellValue($lastPolicyCol . $row, $daysSinceLastPolicy);
                    
                    // Color code based on recency
                    if ($daysSinceLastPolicy <= 7) {
                        // Green for recent activity (last 7 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('4CAF50');
                    } else if ($daysSinceLastPolicy <= 30) {
                        // Yellow for moderate activity (last 30 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC107');
                    } else {
                        // Red for inactive (more than 30 days)
                        $sheet->getStyle($lastPolicyCol . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F44336');
                    }
                    
                    $sheet->getStyle($lastPolicyCol . $row)->getFont()->getColor()
                        ->setRGB('FFFFFF'); // White text
                    $sheet->getStyle($lastPolicyCol . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->setCellValue($lastPolicyCol . $row, 'N/A');
                }
                
                $row++;
            }
            
            // Auto-size columns
            $lastCol = $lastPolicyCol;
            foreach (range('A', $lastCol) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
            
            // Add title and date range
            $sheet->insertNewRowBefore(1, 2);
            $title = 'Agent Policy Performance - Yearly Report';
            $dateRange = 'Period: ' . $fromDate->format('Y') . ' to ' . $toDate->format('Y');
            
            $sheet->setCellValue('A1', $title);
            $sheet->setCellValue('A2', $dateRange);
            
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->mergeCells('A1:' . $lastCol . '1');
            $sheet->getStyle('A1')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
            $sheet->mergeCells('A2:' . $lastCol . '2');
            $sheet->getStyle('A2')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Save the file
        $filename = 'policy_report_' . $reportPeriod . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
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
                    'B1' => 'Mobile Number',
                    'C1' => 'PAN Number',
                    'D1' => 'Status',
                    'E1' => 'Date Joined',
                    'F1' => 'Total Policies',
                    'G1' => 'Total Commission',
                    'H1' => 'Address',
                    'I1' => 'Commission – Last Month Settlement',
                ];
            } else { // customer
                $headers = [
                    'A1' => 'Name',
                    'B1' => 'Mobile Number',
                    'C1' => 'Aadhar Number',
                    'D1' => 'PAN Number',
                    'E1' => 'Status',
                    'F1' => 'Date Joined',
                    'G1' => 'Total Policies',
                    'H1' => 'Total Premium',
                    'I1' => 'Address',
                ];
            }

            foreach ($headers as $cell => $label) {
                $sheet->setCellValue($cell, $label);
            }

            // Fill data
            $row = 2;
            foreach ($users as $user) {
                $sheet->setCellValue('A' . $row, $user->name);
                $sheet->setCellValue('B' . $row, $user->mobile_number);

                // Combine state, city, and address into a single address field
                $fullAddress = implode(', ', array_filter([$user->address, $user->city, $user->state]));
                $sheet->setCellValue('H' . $row, $fullAddress);

                if ($role === 'agent') {
                    // Get agent's policy count and commission with date filters
                    $policyQuery = Policy::where('agent_id', $user->id);

                    // Apply date filters to policy query if provided
                    if ($request->filled('from_date')) {
                        $policyQuery->whereDate('policy_start_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $policyQuery->whereDate('policy_start_date', '<=', $request->to_date);
                    }

                    $policyCount = $policyQuery->count();
                    $totalCommission = $policyQuery->sum('agent_commission');

                    $sheet->setCellValue('C' . $row, $user->pan_number);
                    $sheet->setCellValue('D' . $row, ucfirst($user->status ? 'Active' : 'InActive'));
                    $sheet->setCellValue('E' . $row, $user->created_at->format('Y-m-d'));
                    $sheet->setCellValue('F' . $row, $policyCount);
                    $sheet->setCellValue('G' . $row, $totalCommission);
                    $sheet->setCellValue('I' . $row, $user->commission_settlement ? 'yes' : '-');
                } else {
                    // Get customer's policy count and total premium with date filters
                    $policyQuery = CustomerPolicy::where('user_id', $user->id);

                    // Apply date filters to policy query if provided
                    if ($request->filled('from_date')) {
                        $policyQuery->whereDate('policy_start_date', '>=', $request->from_date);
                    }
                    if ($request->filled('to_date')) {
                        $policyQuery->whereDate('policy_start_date', '<=', $request->to_date);
                    }

                    $policyCount = $policyQuery->count();
                    $totalPremium = $policyQuery->sum('premium');

                    $sheet->setCellValue('C' . $row, $user->aadhar_number);
                    $sheet->setCellValue('D' . $row, $user->pan_number);
                    $sheet->setCellValue('E' . $row, ucfirst($user->status));
                    $sheet->setCellValue('F' . $row, $user->created_at->format('Y-m-d'));
                    $sheet->setCellValue('G' . $row, $policyCount);
                    $sheet->setCellValue('H' . $row, $totalPremium);
                }
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'I') as $column) {
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


    /**
     * Generate and download account report
     */
    public function downloadAccountReport(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
                'agent_id' => 'nullable|exists:users,id',
                'month' => 'nullable|integer|between:1,12',
                'year' => 'nullable|integer|min:2000',
            ]);

            // Start query
            $query = Account::query();

            // Apply filters
            if ($request->filled('from_date')) {
                $query->whereDate('payment_date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('payment_date', '<=', $request->to_date);
            }
            if ($request->filled('agent_id')) {
                $query->where('user_id', $request->agent_id);
            }
            if ($request->filled('month')) {
                $query->whereMonth('payment_date', $request->month);
            }
            if ($request->filled('year')) {
                $query->whereYear('payment_date', $request->year);
            }

            // Fetch records with agent relationship
            $accounts = $query->with(['agent'])->get();

            // Handle no records
            if ($accounts->isEmpty()) {
                return redirect()->back()->with('error', 'No records found for the selected filters.');
            }

            // Start Excel export
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Headers
            $headers = [
                'A1' => 'Agent Name',
                'B1' => 'Payment Date',
                'C1' => 'Amount Paid',
                'D1' => 'Amount Remaining',
                'E1' => 'Payment Method',
                'F1' => 'Transaction ID',
                'G1' => 'Notes',
                // 'H1' => 'Status'
            ];
            foreach ($headers as $cell => $label) {
                $sheet->setCellValue($cell, $label);
            }

            // Fill data
            $row = 2;
            foreach ($accounts as $account) {
                $sheet->setCellValue('A' . $row, optional($account->agent)->name);
                $sheet->setCellValue('B' . $row, $account->payment_date->format('Y-m-d'));
                $sheet->setCellValue('C' . $row, $account->amount_paid);
                $sheet->setCellValue('D' . $row, $account->amount_remaining);
                $sheet->setCellValue('E' . $row, $account->payment_method);
                $sheet->setCellValue('F' . $row, $account->transaction_id);
                $sheet->setCellValue('G' . $row, $account->notes);
                // $sheet->setCellValue('H' . $row, $account->status);
                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Save the file
            $filename = 'account_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/public/reports/' . $filename);

            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            return response()->download($filePath, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Account report export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while generating the report: ' . $e->getMessage());
        }
    }
}
