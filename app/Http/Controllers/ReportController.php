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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $this->validateReportRequest($request);

            // Build base query with filters
            $query = $this->buildPolicyQuery($request);

            // Get date range 
            $fromDate = $request->filled('from_date')
                ? Carbon::parse($request->from_date)
                : Carbon::now()->subMonths(6);
            $toDate = $request->filled('to_date')
                ? Carbon::parse($request->to_date)
                : Carbon::now();

            // Check report period selection
            $reportPeriod = $request->input('report_period', 'daily');

            // Create spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Generate appropriate report based on period
            switch ($reportPeriod) {
                case 'daily':
                    $this->generateDailyReport($sheet, $query);
                    break;
                case 'monthly':
                    $this->generateMonthlyReport($sheet, $query, $fromDate, $toDate);
                    break;
                case 'yearly':
                    $this->generateYearlyReport($sheet, $query, $fromDate, $toDate);
                    break;
            }

            // Save and download the file
            return $this->saveAndDownloadReport($spreadsheet, $reportPeriod);
        } catch (\Exception $e) {
            Log::error('Policy report export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while generating the report: ' . $e->getMessage());
        }
    }

    /**
     * Validate the report request parameters
     */
    private function validateReportRequest(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'company_id' => 'nullable|exists:insurance_companies,id',
            'agent_id' => 'nullable|exists:users,id',
            'policy_type' => 'nullable|exists:insurance_products,id',
            'payment_by' => 'nullable|string',
            'report_period' => 'nullable|in:daily,monthly,yearly',
        ]);
    }

    /**
     * Build policy query with filters
     */
    private function buildPolicyQuery(Request $request)
    {
        $query = Policy::query();

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

        return $query;
    }

    /**
     * Generate daily detailed report
     */
    private function generateDailyReport($sheet, $query)
    {
        // Fetch all policies using the filtered query
        $policies = $query->with(['agent', 'company', 'insuranceProduct'])->get();

        // Check if policies exist
        if ($policies->isEmpty()) {
            throw new \Exception('No records found for the selected filters.');
        }

        // Set headers
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
    }

    /**
     * Generate monthly report with added metrics
     */
    private function generateMonthlyReport($sheet, $query, $fromDate, $toDate)
    {
        // Create array of months for column headers
        $months = $this->getMonthsArray($fromDate, $toDate);

        // Get agents with policies in the date range
        $agents = $this->getAgentsWithPolicies($query);

        // If no agents found
        if ($agents->isEmpty()) {
            throw new \Exception('No records found for the selected filters.');
        }

        // Calculate last policy dates and differences for each agent
        $lastPolicyDates = $this->calculateLastPolicyDates();

        // Set up headers
        $sheet->setCellValue('A1', 'Agent');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // Set month headers
        $col = 'B';
        foreach ($months as $month) {
            $sheet->setCellValue($col . '1', $month['display']);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Add metrics headers
        $totalCol = $col;
        $sheet->setCellValue($totalCol . '1', 'Total');
        $sheet->getStyle($totalCol . '1')->getFont()->setBold(true);
        $col++;

        $lastMonthsDiffCol = $col;
        $sheet->setCellValue($lastMonthsDiffCol . '1', 'Last 2 Months Diff');
        $sheet->getStyle($lastMonthsDiffCol . '1')->getFont()->setBold(true);
        $col++;

        $daysSinceCol = $col;
        $sheet->setCellValue($daysSinceCol . '1', 'Days Since Last Policy');
        $sheet->getStyle($daysSinceCol . '1')->getFont()->setBold(true);
        $col++;

        $trendCol = $col;
        $sheet->setCellValue($trendCol . '1', 'Trend');
        $sheet->getStyle($trendCol . '1')->getFont()->setBold(true);

        // Fill data for each agent
        $row = 2;
        foreach ($agents as $agent) {
            if (!$agent) continue;

            $sheet->setCellValue('A' . $row, $agent->name);

            $totalPolicies = 0;
            $monthlyData = [];
            $col = 'B';

            // Process each month's data
            foreach ($months as $month) {
                $monthStart = Carbon::createFromFormat('Y-m', $month['year_month'])->startOfMonth();
                $monthEnd = Carbon::createFromFormat('Y-m', $month['year_month'])->endOfMonth();

                // Get count of policies for this month
                $policyCount = (clone $query)
                    ->where('agent_id', $agent->id)
                    ->whereDate('policy_start_date', '>=', $monthStart)
                    ->whereDate('policy_start_date', '<=', $monthEnd)
                    ->count();

                $sheet->setCellValue($col . $row, $policyCount);
                $monthlyData[] = $policyCount;
                $totalPolicies += $policyCount;
                $col++;
            }

            // Set total policies
            $sheet->setCellValue($totalCol . $row, $totalPolicies);
            $sheet->getStyle($totalCol . $row)->getFont()->setBold(true);

            // Calculate and set last 2 months difference
            $lastTwoMonthsDiff = $this->calculateLastTwoMonthsDiff($monthlyData);
            $sheet->setCellValue($lastMonthsDiffCol . $row, $lastTwoMonthsDiff);
            $this->formatDiffCell($sheet, $lastMonthsDiffCol . $row, $lastTwoMonthsDiff);

            // Set days since last policy
            $daysSinceLastPolicy = isset($lastPolicyDates[$agent->id]) ? $lastPolicyDates[$agent->id] : 'N/A';
            $sheet->setCellValue($daysSinceCol . $row, $daysSinceLastPolicy);
            $this->formatDaysSinceCell($sheet, $daysSinceCol . $row, $daysSinceLastPolicy);

            // Set trend indicator
            $trend = $this->determineTrend($lastTwoMonthsDiff);
            $sheet->setCellValue($trendCol . $row, $trend);
            $this->formatTrendCell($sheet, $trendCol . $row, $trend);

            $row++;
        }

        // Auto-size columns
        foreach (range('A', $trendCol) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add title and date range
        $sheet->insertNewRowBefore(1, 2);
        $title = 'Agent Policy Performance - Monthly Report';
        $dateRange = 'Period: ' . $fromDate->format('M Y') . ' to ' . $toDate->format('M Y');

        $sheet->setCellValue('A1', $title);
        $sheet->setCellValue('A2', $dateRange);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:' . $trendCol . '1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:' . $trendCol . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Generate yearly report with added metrics
     */
    private function generateYearlyReport($sheet, $query, $fromDate, $toDate)
    {
        // Create array of years for column headers
        $years = $this->getYearsArray($fromDate, $toDate);

        // Get agents with policies in the date range
        $agents = $this->getAgentsWithPolicies($query);

        // If no agents found
        if ($agents->isEmpty()) {
            throw new \Exception('No records found for the selected filters.');
        }

        // Calculate last policy dates and differences for each agent
        $lastPolicyDates = $this->calculateLastPolicyDates();

        // Set up headers
        $sheet->setCellValue('A1', 'Agent');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // Set year headers
        $col = 'B';
        foreach ($years as $year) {
            $sheet->setCellValue($col . '1', $year['display']);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        // Add metrics headers
        $totalCol = $col;
        $sheet->setCellValue($totalCol . '1', 'Total');
        $sheet->getStyle($totalCol . '1')->getFont()->setBold(true);
        $col++;

        // $lastMonthsDiffCol = $col;
        // $sheet->setCellValue($lastMonthsDiffCol . '1', 'Last 2 Months Diff');
        // $sheet->getStyle($lastMonthsDiffCol . '1')->getFont()->setBold(true);
        // $col++;

        // $daysSinceCol = $col;
        // $sheet->setCellValue($daysSinceCol . '1', 'Days Since Last Policy');
        // $sheet->getStyle($daysSinceCol . '1')->getFont()->setBold(true);
        // $col++;

        // $trendCol = $col;
        // $sheet->setCellValue($trendCol . '1', 'Trend');
        // $sheet->getStyle($trendCol . '1')->getFont()->setBold(true);

        // Fill data for each agent
        $row = 2;
        foreach ($agents as $agent) {
            if (!$agent) continue;

            $sheet->setCellValue('A' . $row, $agent->name);

            $totalPolicies = 0;
            $yearlyData = [];
            $col = 'B';

            // Calculate policy counts for each year
            foreach ($years as $year) {
                $yearStart = Carbon::createFromFormat('Y', $year['year'])->startOfYear();
                $yearEnd = Carbon::createFromFormat('Y', $year['year'])->endOfYear();

                $policyCount = (clone $query)
                    ->where('agent_id', $agent->id)
                    ->whereDate('policy_start_date', '>=', $yearStart)
                    ->whereDate('policy_start_date', '<=', $yearEnd)
                    ->count();

                $sheet->setCellValue($col . $row, $policyCount);

                if ($policyCount > 0) {
                    $sheet->getStyle($col . $row)->getAlignment()
                        ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }

                $yearlyData[] = $policyCount;
                $totalPolicies += $policyCount;
                $col++;
            }

            // Set total policies
            $sheet->setCellValue($totalCol . $row, $totalPolicies);
            $sheet->getStyle($totalCol . $row)->getFont()->setBold(true);

            // Calculate and set last 2 months difference
            // For yearly report, this represents the last 2 months of the most recent year
            $lastTwoMonthsDiff = $this->calculateLastTwoMonthsOfYearDiff($agent->id, $toDate->year);
            // $sheet->setCellValue($lastMonthsDiffCol . $row, $lastTwoMonthsDiff);
            // $this->formatDiffCell($sheet, $lastMonthsDiffCol . $row, $lastTwoMonthsDiff);

            // Set days since last policy
            $daysSinceLastPolicy = isset($lastPolicyDates[$agent->id]) ? $lastPolicyDates[$agent->id] : 'N/A';
            // $sheet->setCellValue($daysSinceCol . $row, $daysSinceLastPolicy);
            // $this->formatDaysSinceCell($sheet, $daysSinceCol . $row, $daysSinceLastPolicy);

            // Set trend indicator
            $trend = $this->determineTrend($lastTwoMonthsDiff);
            // $sheet->setCellValue($trendCol . $row, $trend);
            // $this->formatTrendCell($sheet, $trendCol . $row, $trend);

            $row++;
        }

        // Auto-size columns
        // foreach (range('A', $trendCol) as $column) {
        //     $sheet->getColumnDimension($column)->setAutoSize(true);
        // }

        // Add title and date range
        $sheet->insertNewRowBefore(1, 2);
        $title = 'Agent Policy Performance - Yearly Report';
        $dateRange = 'Period: ' . $fromDate->format('Y') . ' to ' . $toDate->format('Y');

        $sheet->setCellValue('A1', $title);
        $sheet->setCellValue('A2', $dateRange);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
       // $sheet->mergeCells('A1:' . $trendCol . '1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

       // $sheet->mergeCells('A2:' . $trendCol . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Save and download the Excel report
     */
    private function saveAndDownloadReport($spreadsheet, $reportPeriod)
    {
        $filename = 'policy_report_' . $reportPeriod . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/public/reports/' . $filename);

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Get array of months between date range
     */
    private function getMonthsArray($fromDate, $toDate)
    {
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

        return $months;
    }

    /**
     * Get array of years between date range
     */
    private function getYearsArray($fromDate, $toDate)
    {
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

        return $years;
    }

    /**
     * Get agents who have policies in the date range
     */
    private function getAgentsWithPolicies($query)
    {
        $agentIds = (clone $query)->select('agent_id')
            ->groupBy('agent_id')
            ->pluck('agent_id')
            ->filter();

        return User::whereIn('id', $agentIds)->get();
    }

    /**
     * Calculate days since last policy for each agent
     */
    private function calculateLastPolicyDates()
    {
        $today = Carbon::now();
        $latestPolicyDates = Policy
            ::select('agent_id',DB::raw('MAX(DATE(policy_start_date)) as last_policy_date'))
            ->groupBy('agent_id')
            ->get();

        $daysSinceLastPolicy = [];

        foreach ($latestPolicyDates as $record) {
            $lastPolicyDate = Carbon::parse($record->last_policy_date);
            $daysDifference = $today->diffInDays($lastPolicyDate);
            $daysSinceLastPolicy[$record->agent_id] = $daysDifference;
        }

        return $daysSinceLastPolicy;
    }

    /**
     * Calculate difference between last two months in data array
     */
    private function calculateLastTwoMonthsDiff($monthlyData)
    {
        if (count($monthlyData) < 2) {
            return 0;
        }

        $lastMonth = $monthlyData[count($monthlyData) - 1];
        $secondLastMonth = $monthlyData[count($monthlyData) - 2];

        return $lastMonth - $secondLastMonth;
    }

    /**
     * Calculate difference between last two months of a specific year
     */
    private function calculateLastTwoMonthsOfYearDiff($agentId, $year)
    {
        $lastMonthOfYear = Carbon::createFromDate($year, 12, 1);
        $secondLastMonthOfYear = Carbon::createFromDate($year, 11, 1);

        $lastMonthCount = Policy::where('agent_id', $agentId)
            ->whereYear('policy_start_date', $year)
            ->whereMonth('policy_start_date', 12)
            ->count();

        $secondLastMonthCount = Policy::where('agent_id', $agentId)
            ->whereYear('policy_start_date', $year)
            ->whereMonth('policy_start_date', 11)
            ->count();

        return $lastMonthCount - $secondLastMonthCount;
    }

    /**
     * Determine trend based on difference
     */
    private function determineTrend($diff)
    {
        if ($diff > 0) {
            return 'up';
        } elseif ($diff < 0) {
            return 'down';
        } else {
            return 'same';
        }
    }

    /**
     * Format diff cell based on value
     */
    private function formatDiffCell($sheet, $cell, $diff)
    {
        if ($diff > 0) {
            $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));
            $sheet->setCellValue($cell, '+' . $diff);
        } elseif ($diff < 0) {
            $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
        }
    }

    /**
     * Format days since cell based on value
     */
    private function formatDaysSinceCell($sheet, $cell, $days)
    {
        if (is_numeric($days)) {
            if ($days > 60) {
                $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
                $sheet->getStyle($cell)->getFont()->setBold(true);
            } elseif ($days > 30) {
                $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKYELLOW));
            } else {
                $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));
            }
        }
    }

    /**
     * Format trend cell based on value
     */
    private function formatTrendCell($sheet, $cell, $trend)
    {
        if ($trend == 'up') {
            $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN));
            $sheet->setCellValue($cell, '↑ Up');
        } elseif ($trend == 'down') {
            $sheet->getStyle($cell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
            $sheet->setCellValue($cell, '↓ Down');
        } else {
            $sheet->setCellValue($cell, '→ Same');
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
                'due_date_option' => 'nullable|string|in:overdue,due_today,due_in_7_days,due_in_15_days,due_in_30_days,due_this_month,due_next_month',
            ]);

            $role = $request->role;

            if ($role === 'agent') {
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
            }

            // Start Excel export
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

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

                    $row++;
                }
                // Auto-size columns
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            } else { // customer
                $headers = [
                    'A1' => 'Customer Name',
                    'B1' => 'Mobile Number',
                    'C1' => 'Policy Number',
                    'D1' => 'Product Name',
                    'E1' => 'Insurance Company',
                    'F1' => 'Policy Type',
                    'G1' => 'Policy Start Date',
                    'H1' => 'Due Date',
                    'I1' => 'Premium',
                    'J1' => 'Net Amount',
                    'K1' => 'GST',
                ];
                foreach ($headers as $cell => $label) {
                    $sheet->setCellValue($cell, $label);
                }

                $policyQuery = CustomerPolicy::with(['user', 'product']);

                if ($request->filled('user_id')) {
                    $policyQuery->where('user_id', $request->user_id);
                }

                if ($request->filled('due_date_option')) {
                    $today = Carbon::today();
                    switch ($request->due_date_option) {
                        case 'overdue':
                            $policyQuery->whereDate('policy_end_date', '<', $today);
                            break;
                        case 'due_today':
                            $policyQuery->whereDate('policy_end_date', $today);
                            break;
                        case 'due_in_7_days':
                            $policyQuery->whereBetween('policy_end_date', [$today, $today->copy()->addDays(7)]);
                            break;
                        case 'due_in_15_days':
                            $policyQuery->whereBetween('policy_end_date', [$today, $today->copy()->addDays(15)]);
                            break;
                        case 'due_in_30_days':
                            $policyQuery->whereBetween('policy_end_date', [$today, $today->copy()->addDays(30)]);
                            break;
                            case 'due_this_month':
                                $policyQuery->whereYear('policy_end_date', $today->year)->whereMonth('policy_end_date', $today->month);
                                break;
                            case 'due_next_month':
                                $nextMonth = $today->copy()->addMonth();
                                $policyQuery->whereYear('policy_end_date', $nextMonth->year)->whereMonth('policy_end_date', $nextMonth->month);
                                break;
                        }
                }

                $policies = $policyQuery->get();

                if ($policies->isEmpty()) {
                    return redirect()->back()->with('error', "No policies found for the selected filters.");
                }

                $row = 2;
                foreach ($policies as $policy) {
                    $sheet->setCellValue('A' . $row, $policy->user->name);
                    $sheet->setCellValue('B' . $row, $policy->user->mobile_number);
                    $sheet->setCellValue('C' . $row, $policy->policy_no);
                    $sheet->setCellValue('D' . $row, $policy->product ? $policy->product->name : 'N/A');
                    $sheet->setCellValue('E' . $row, $policy->insurance_company);
                    $sheet->setCellValue('F' . $row, $policy->policy_type);
                    $sheet->setCellValue('G' . $row, Carbon::parse($policy->policy_start_date)->format('Y-m-d'));
                    $sheet->setCellValue('H' . $row, Carbon::parse($policy->policy_end_date)->format('Y-m-d'));
                    $sheet->setCellValue('I' . $row, $policy->premium);
                    $sheet->setCellValue('J' . $row, $policy->net_amount);
                    $sheet->setCellValue('K' . $row, $policy->gst);
                    $row++;
                }

                // Auto-size columns
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
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
            Log::error($request->role . ' report export failed: ' . $e->getMessage());
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
            Log::error('Account report export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while generating the report: ' . $e->getMessage());
        }
    }
}
