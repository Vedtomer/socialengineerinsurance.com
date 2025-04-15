<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Policy;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppMessages;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Validators\ValidationException;

class PolicyController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->isMethod('get')) {
            // Return the combined view instead of separate upload view
            return view('admin.unified_policy_upload');
        }

        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.oasis.opendocument.spreadsheet',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Store file with a unique name to avoid conflicts
            $fileName = time() . '_' . $request->file('excelFile')->getClientOriginalName();
            $filePath = $request->file('excelFile')->storeAs('temp', $fileName);

            // Initialize import class
            $importClass = new ExcelImport($request->date);

            // Process the import - wrap in DB transaction
            \DB::beginTransaction();
            try {
                Excel::import($importClass, $filePath);
                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e; // Re-throw to be caught by outer catch
            }

            // Clean up temp file after import
            Storage::delete($filePath);

            // Record success stats
            $stats = $importClass->getImportStats();



            return redirect()->back()->with([
                'success' => 'Data imported successfully!',
                'stats' => $stats,
                'activeTab' => 'excel'
            ]);
        } catch (ValidationException $e) {
            // Handle Excel validation errors
            $failures = $e->failures();
            $errors = [];

            foreach ($failures as $failure) {
                $rowNumber = $failure->row();
                $errors[] = "Row {$rowNumber}: " . implode(', ', $failure->errors());
            }

            Log::error('Excel import validation failed: ' . json_encode($errors));
            return redirect()->back()->withErrors(['import_errors' => $errors])->withInput();
        } catch (\Exception $e) {
            // Handle general exceptions
            Log::error('Excel import failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Import failed: ' . $e->getMessage()])->withInput()->with('activeTab', 'excel');
        }
    }


    public function PolicyList(Request $request)
    {
        list($agent_id, $start_date, $end_date) = prepareDashboardData($request);

        $query = Policy::with('agent', 'company', 'insuranceProduct')
            ->where('deleted_at', null)
            ->whereBetween('policy_start_date', [$start_date, $end_date])
            ->select(
                '*',
                DB::raw('CASE WHEN payment_by = "commission_deducted" THEN agent_commission ELSE 0 END as commission_deducted'),
                DB::raw('CASE WHEN payment_by = "pay_later_with_adjustment" THEN agent_commission ELSE 0 END as commission_will_adjustment'),
                DB::raw('CASE WHEN payment_by = "pay_later" THEN premium ELSE 0 END as full_due_amount'),
                DB::raw('CASE WHEN payment_by = "pay_later_with_adjustment" THEN (premium - agent_commission) ELSE 0 END as partial_due_amount')
            )
            ->orderBy('id', 'desc');

        if (!empty($agent_id)) {
            $query->where('agent_id', $agent_id);
        }

        $data = $query->get();
        $agentData = User::role('agent')->get();

        // Get total payments from Account model for the selected date range
        $totalPayments = Account::whereBetween('payment_date', [$start_date, $end_date]);

        // Filter by agent if specified
        if (!empty($agent_id)) {
            $totalPayments->where('user_id', $agent_id);
        }

        // Get the sum of all payments
        $totalAmountPaid = $totalPayments->sum('amount_paid');

        // Calculate analytics
        $analytics = [
            'total_policies' => $data->count(),
            'total_premium' => $data->sum('premium'),
            'total_gst' => $data->sum('gst'),
            'total_net_amount' => $data->sum('net_amount'),
            'total_commission' => $data->sum('agent_commission'),
            'total_commission_deducted' => $data->sum('commission_deducted'),
            'total_commission_will_adjustment' => $data->sum('commission_will_adjustment'),
            'Net_Commission_Payable_Agent' => ($data->sum('agent_commission') - $data->sum('commission_deducted') -
                $data->sum('commission_will_adjustment')),
            'total_payout' => $data->sum('payout'),

            // Due amount calculations with Account model data
            'total_amount_due_agents' => $data->sum('full_due_amount') + $data->sum('partial_due_amount'),
            'total_amount_paid_agents' => $totalAmountPaid,
            'pending_amount_due' => ($data->sum('full_due_amount') + $data->sum('partial_due_amount')) - $totalAmountPaid
        ];

        // Format currency values for display
        $analytics['total_premium'] = number_format($analytics['total_premium'], 2);
        $analytics['total_commission'] = number_format($analytics['total_commission'], 2);
        $analytics['total_net_amount'] = number_format($analytics['total_net_amount'], 2);
        $analytics['total_payout'] = number_format($analytics['total_payout'], 2);
        $analytics['total_amount_due_agents'] = number_format($analytics['total_amount_due_agents'], 2);
        $analytics['total_amount_paid_agents'] = number_format($analytics['total_amount_paid_agents'], 2);
        $analytics['pending_amount_due'] = number_format($analytics['pending_amount_due'], 2);

        return view('admin.policy_list', [
            'data' => $data,
            'agentData' => $agentData,
            'analytics' => $analytics
        ]);
    }


    public function policyDelete(Request $request, $id)
    {

        $policy = Policy::findOrFail($id);
        $policy->delete();
        return response()->json(['success' => 'Policy Delete successful.']);
    }


    public function policyUpload(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('admin.unified_policy_upload');
        }

        $successFiles = [];
        $failedFiles = [];

        try {
            $validator = Validator::make($request->all(), [
                'files.*' => 'required|mimes:pdf',
            ]);

            if ($validator->fails()) {
                throw new \Exception('One or more files are invalid.');
            }

            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $uniqueName = $originalName;

                try {
                    $file->storeAs('public/policies', $uniqueName);
                    $successFiles[] = $originalName;
                } catch (\Exception $e) {
                    $failedFiles[$originalName] = $e->getMessage();
                }
            }

            // Return the combined view with PDF upload results
            return view('admin.unified_policy_upload', compact('successFiles', 'failedFiles'))->with('activeTab', 'pdf');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput()->with('activeTab', 'pdf');
        }
    }




    public function showPolicyRates()
    {
        $policyData = $this->getMonthlyPolicyRates();
        $policyRates = $policyData['agentData'];
        $chartData = $policyData['chartData'];
        return view('admin.analytics.policy_rates', compact('policyRates', 'chartData'));
    }

    public function getMonthlyPolicyRates()
    {
        $currentDate = now();

        // Set start date to 12 months ago from current month for chart data
        $startDate = Carbon::create($currentDate->year, $currentDate->month, 1)->subMonths(11)->startOfDay();
        $endDate = Carbon::create($currentDate->year, $currentDate->month, $currentDate->daysInMonth)->endOfDay();

        $policyRates = Policy::join('users', 'policies.agent_id', '=', 'users.id')
            ->select(
                'users.name as agent_name',
                'policies.agent_id',
                DB::raw('YEAR(policies.policy_start_date) as year'),
                DB::raw('MONTH(policies.policy_start_date) as month'),
                DB::raw('COUNT(DISTINCT policies.id) as policy_count')
            )
            ->where('users.status', '=', 1)
            ->where('policies.policy_start_date', '>=', $startDate)
            ->where('policies.policy_start_date', '<=', $endDate)
            ->groupBy('policies.agent_id', 'year', 'month', 'users.name')
            ->get();

        $formattedData = [];

        // Create array of month names from current month back 12 months for chart data
        $monthNames = [];
        $monthYears = []; // Store both month and year for accurate matching

        for ($i = 11; $i >= 0; $i--) {
            $monthDate = Carbon::create($currentDate->year, $currentDate->month, 1)->subMonths($i);
            $monthNames[] = $monthDate->format('Y M');
            $monthYears[] = [
                'year' => $monthDate->year,
                'month' => $monthDate->month
            ];
        }

        // Initialize policyCounts for all months
        $policyCounts = array_fill(0, 12, 0);

        // For agent data, we only want the last 6 months
        $lastSixMonthNames = array_slice($monthNames, 6, 6);
        $lastSixMonthYears = array_slice($monthYears, 6, 6);

        foreach ($policyRates as $rate) {
            if (!isset($formattedData[$rate->agent_id])) {
                $formattedData[$rate->agent_id] = [
                    'agent_name' => $rate->agent_name,
                    'labels' => $lastSixMonthNames, // Only last 6 months for agent data
                    'data' => array_fill(0, 6, 0)   // Only 6 data points
                ];
            }

            // For chart data - all 12 months
            for ($i = 0; $i < 12; $i++) {
                if ($monthYears[$i]['year'] == $rate->year && $monthYears[$i]['month'] == $rate->month) {
                    $policyCounts[$i] += $rate->policy_count;

                    // For agent data - only if it's in the last 6 months
                    if ($i >= 6) {
                        $formattedData[$rate->agent_id]['data'][$i - 6] = $rate->policy_count;
                    }
                    break;
                }
            }
        }

        // Prepare chart data
        $chartData = [
            'categories' => array_map(
                function ($month, $count) {
                    return "$month($count)";
                },
                $monthNames,
                $policyCounts
            ),
            'data' => $policyCounts,
            'series' => [
                [
                    'name' => 'Policy Count',
                    'data' => $policyCounts
                ]
            ]
        ];

        return [
            'agentData' => $this->addDaysSinceLastPolicy($formattedData),
            'chartData' => $chartData
        ];
    }



    public function addDaysSinceLastPolicy($formattedData)
    {

        $today = strtotime(date('Y-m-d'));
        $latestPolicyDates = DB::table('policies')
            ->select('agent_id', DB::raw('MAX(DATE(policy_start_date)) as last_policy_date'))
            ->groupBy('agent_id')
            ->get()
            ->keyBy('agent_id');

        foreach ($formattedData as $agentId => &$agentData) {
            if (isset($latestPolicyDates[$agentId])) {
                $lastPolicyDate = strtotime($latestPolicyDates[$agentId]->last_policy_date);

                // Calculate the difference in days
                $daysDifference = ($today - $lastPolicyDate) / (60 * 60 * 24);

                // Ensure the difference is not negative
                $agentData['days_since_last_policy'] = max(0, (int)$daysDifference);
            } else {
                // If no policy found for the agent, set a high number or handle as needed
                $agentData['days_since_last_policy'] = 9999;
            }
        }

        uasort($formattedData, function ($a, $b) {
            return $b['days_since_last_policy'] <=> $a['days_since_last_policy'];
        });

        return $formattedData;
    }
}
