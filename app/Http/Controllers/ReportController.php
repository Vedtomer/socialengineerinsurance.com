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
                // Monthly report - group by month and agent
                $policies = $query->selectRaw('
                    COUNT(*) as policy_count,
                    DATE_FORMAT(policy_start_date, "%Y-%m") as month,
                    agent_id,
                    SUM(premium) as total_premium,
                    SUM(gst) as total_gst,
                    SUM(agent_commission) as total_commission,
                    SUM(net_amount) as total_net_amount,
                    SUM(discount) as total_discount,
                    SUM(payout) as total_payout
                ')
                    ->with(['agent']) // Eager load agent relationship
                    ->groupBy(['month', 'agent_id'])
                    ->orderBy('month')
                    ->orderBy('agent_id')
                    ->get();

                // Handle no records
                if ($policies->isEmpty()) {
                    return redirect()->back()->with('error', 'No records found for the selected filters.');
                }

                // Headers for monthly report
                $headers = [
                    'A1' => 'Month',
                    'B1' => 'Agent',
                    'C1' => 'Policy Count',
                    'D1' => 'Total Premium',
                    'E1' => 'Total GST',
                    'F1' => 'Total Commission',
                    'G1' => 'Total Net Amount',
                    'H1' => 'Total Discount',
                    'I1' => 'Total Payout',
                ];
                foreach ($headers as $cell => $label) {
                    $sheet->setCellValue($cell, $label);
                }

                // Fill data
                $row = 2;
                foreach ($policies as $policy) {
                    $sheet->setCellValue('A' . $row, Carbon::createFromFormat('Y-m', $policy->month)->format('F Y'));
                    $sheet->setCellValue('B' . $row, optional($policy->agent)->name ?? 'Unknown');
                    $sheet->setCellValue('C' . $row, $policy->policy_count);
                    $sheet->setCellValue('D' . $row, $policy->total_premium);
                    $sheet->setCellValue('E' . $row, $policy->total_gst);
                    $sheet->setCellValue('F' . $row, $policy->total_commission);
                    $sheet->setCellValue('G' . $row, $policy->total_net_amount);
                    $sheet->setCellValue('H' . $row, $policy->total_discount);
                    $sheet->setCellValue('I' . $row, $policy->total_payout);
                    $row++;
                }

                // Add monthly totals at the bottom
                $summaryData = $policies->groupBy('month')->map(function ($group) {
                    return [
                        'month' => $group->first()->month,
                        'policy_count' => $group->sum('policy_count'),
                        'total_premium' => $group->sum('total_premium'),
                        'total_gst' => $group->sum('total_gst'),
                        'total_commission' => $group->sum('total_commission'),
                        'total_net_amount' => $group->sum('total_net_amount'),
                        'total_discount' => $group->sum('total_discount'),
                        'total_payout' => $group->sum('total_payout'),
                    ];
                })->values();

                // Add a blank row
                $row++;

                // Add monthly summary header
                $sheet->setCellValue('A' . $row, 'MONTHLY TOTALS');
                $sheet->mergeCells('A' . $row . ':I' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $row++;

                // Add summary headers
                $summaryHeaders = [
                    'A' . $row => 'Month',
                    'B' . $row => 'Total Policies',
                    'C' . $row => 'Total Premium',
                    'D' . $row => 'Total GST',
                    'E' . $row => 'Total Commission',
                    'F' . $row => 'Total Net Amount',
                    'G' . $row => 'Total Discount',
                    'H' . $row => 'Total Payout',
                ];
                foreach ($summaryHeaders as $cell => $label) {
                    $sheet->setCellValue($cell, $label);
                    $sheet->getStyle($cell)->getFont()->setBold(true);
                }
                $row++;

                // Add summary data
                foreach ($summaryData as $summary) {
                    $sheet->setCellValue('A' . $row, Carbon::createFromFormat('Y-m', $summary['month'])->format('F Y'));
                    $sheet->setCellValue('B' . $row, $summary['policy_count']);
                    $sheet->setCellValue('C' . $row, $summary['total_premium']);
                    $sheet->setCellValue('D' . $row, $summary['total_gst']);
                    $sheet->setCellValue('E' . $row, $summary['total_commission']);
                    $sheet->setCellValue('F' . $row, $summary['total_net_amount']);
                    $sheet->setCellValue('G' . $row, $summary['total_discount']);
                    $sheet->setCellValue('H' . $row, $summary['total_payout']);
                    $row++;
                }

                // Auto-size columns
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            } else { // yearly
                // Yearly report - group by year and agent
                $policies = $query->selectRaw('
                    COUNT(*) as policy_count,
                    YEAR(policy_start_date) as year,
                    agent_id,
                    SUM(premium) as total_premium,
                    SUM(gst) as total_gst,
                    SUM(agent_commission) as total_commission,
                    SUM(net_amount) as total_net_amount,
                    SUM(discount) as total_discount,
                    SUM(payout) as total_payout
                ')
                    ->with(['agent']) // Eager load agent relationship
                    ->groupBy(['year', 'agent_id'])
                    ->orderBy('year')
                    ->orderBy('agent_id')
                    ->get();

                // Handle no records
                if ($policies->isEmpty()) {
                    return redirect()->back()->with('error', 'No records found for the selected filters.');
                }

                // Headers for yearly report
                $headers = [
                    'A1' => 'Year',
                    'B1' => 'Agent',
                    'C1' => 'Policy Count',
                    'D1' => 'Total Premium',
                    'E1' => 'Total GST',
                    'F1' => 'Total Commission',
                    'G1' => 'Total Net Amount',
                    'H1' => 'Total Discount',
                    'I1' => 'Total Payout',
                ];
                foreach ($headers as $cell => $label) {
                    $sheet->setCellValue($cell, $label);
                }

                // Fill data
                $row = 2;
                foreach ($policies as $policy) {
                    $sheet->setCellValue('A' . $row, $policy->year);
                    $sheet->setCellValue('B' . $row, optional($policy->agent)->name ?? 'Unknown');
                    $sheet->setCellValue('C' . $row, $policy->policy_count);
                    $sheet->setCellValue('D' . $row, $policy->total_premium);
                    $sheet->setCellValue('E' . $row, $policy->total_gst);
                    $sheet->setCellValue('F' . $row, $policy->total_commission);
                    $sheet->setCellValue('G' . $row, $policy->total_net_amount);
                    $sheet->setCellValue('H' . $row, $policy->total_discount);
                    $sheet->setCellValue('I' . $row, $policy->total_payout);
                    $row++;
                }

                // Add yearly totals at the bottom
                $summaryData = $policies->groupBy('year')->map(function ($group) {
                    return [
                        'year' => $group->first()->year,
                        'policy_count' => $group->sum('policy_count'),
                        'total_premium' => $group->sum('total_premium'),
                        'total_gst' => $group->sum('total_gst'),
                        'total_commission' => $group->sum('total_commission'),
                        'total_net_amount' => $group->sum('total_net_amount'),
                        'total_discount' => $group->sum('total_discount'),
                        'total_payout' => $group->sum('total_payout'),
                    ];
                })->values();

                // Add a blank row
                $row++;

                // Add yearly summary header
                $sheet->setCellValue('A' . $row, 'YEARLY TOTALS');
                $sheet->mergeCells('A' . $row . ':I' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setBold(true);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $row++;

                // Add summary headers
                $summaryHeaders = [
                    'A' . $row => 'Year',
                    'B' . $row => 'Total Policies',
                    'C' . $row => 'Total Premium',
                    'D' . $row => 'Total GST',
                    'E' . $row => 'Total Commission',
                    'F' . $row => 'Total Net Amount',
                    'G' . $row => 'Total Discount',
                    'H' . $row => 'Total Payout',
                ];
                foreach ($summaryHeaders as $cell => $label) {
                    $sheet->setCellValue($cell, $label);
                    $sheet->getStyle($cell)->getFont()->setBold(true);
                }
                $row++;

                // Add summary data
                foreach ($summaryData as $summary) {
                    $sheet->setCellValue('A' . $row, $summary['year']);
                    $sheet->setCellValue('B' . $row, $summary['policy_count']);
                    $sheet->setCellValue('C' . $row, $summary['total_premium']);
                    $sheet->setCellValue('D' . $row, $summary['total_gst']);
                    $sheet->setCellValue('E' . $row, $summary['total_commission']);
                    $sheet->setCellValue('F' . $row, $summary['total_net_amount']);
                    $sheet->setCellValue('G' . $row, $summary['total_discount']);
                    $sheet->setCellValue('H' . $row, $summary['total_payout']);
                    $row++;
                }

                // Auto-size columns
                foreach (range('A', 'I') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }

            // Apply styles to header row
            $headerStyle = $sheet->getStyle('A1:' . ($reportPeriod === 'daily' ? 'N1' : 'I1'));
            $headerStyle->getFont()->setBold(true);
            $headerStyle->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('DDEBF7');
            $headerStyle->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

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
