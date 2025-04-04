<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Policy;
use App\Models\Transaction;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppMessages;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PolicyController extends Controller
{
    public function upload(Request $request)
    {

        if ($request->isMethod('get')) {
            return view('admin.upload');
        }

        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.oasis.opendocument.spreadsheet',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $importClass = new ExcelImport($request->date);
        $items = $request->file('excelFile')->store('temp');
        Excel::import($importClass, $items);

        // Dispatch job to send WhatsApp messages
        dispatch(new SendWhatsAppMessages($request->date));
        return redirect()->back()->with('success', 'Data imported successfully!');
    }

    public function PolicyList(Request $request)
    {
        list($agent_id, $start_date, $end_date) = prepareDashboardData($request);

        $query = Policy::with('agent', 'company')->where('deleted_at', null)->whereBetween('policy_start_date', [$start_date, $end_date])->orderBy('id', 'desc');

        if (!empty($agent_id)) {
            $query->where('agent_id', $agent_id);
        }
        $data = $query->get();
        $agentData = User::role('agent')->get();

        return view('admin.policy_list', ['data' => $data, 'agentData' => $agentData]);
    }


    public function policyDelete(Request $request, $id)
    {
        // $policyDelete = Policy::where('policy_no', $policy_no)->firstOrFail();
        // $policyDelete->is_deleted = 0;
        // $policyDelete->save();
        $policy = Policy::findOrFail($id);
        $policy->delete(); // Soft delete the policy
        return response()->json(['success' => 'Policy Delete successful.']);
    }


    public function policyUpload(Request $request)
    {

        if ($request->isMethod('get')) {
            return view('admin.policy_pdf_upload');
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

            return view('admin.policy_pdf_upload', compact('successFiles', 'failedFiles'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()])->withInput();
        }
    }

    public function panddingblance(Request $request)
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // Check if start date and end date are provided, otherwise set them accordingly
        $start_date = $request->input('start_date') ?? Carbon::now()->startOfMonth();
        $end_date = $request->input('end_date') ?? Carbon::today();
        $agent_id = $request->input('agent_id') ?? "";

        // Parse dates if they are not null
        if ($start_date !== null) {
            $start_date = Carbon::parse($start_date);
        }

        if ($end_date !== null) {
            $end_date = Carbon::parse($end_date);
        }
        $policy = DB::table('policies')
            ->whereBetween('policy_start_date', [$start_date, $end_date])
            ->leftJoin('agents', function ($join) {
                $join->on('policies.agent_id', '=', 'agents.id');
            })
            ->leftJoin(DB::raw('(SELECT agent_id, SUM(amount) as total_amount,created_at FROM transactions GROUP BY agent_id) AS trans'), function ($join) use ($start_date, $end_date) {
                $join->on('policies.agent_id', '=', 'trans.agent_id')
                    ->whereBetween(DB::raw('trans.created_at'), [$start_date, $end_date]);
            })
            ->where('payment_by', 'SELF')
            ->select(
                'policies.agent_id',
                'agents.name',
                DB::raw('SUM(policies.premium) as total_premium'),
                DB::raw('SUM(CASE WHEN agents.cut_and_pay = 1 THEN policies.agent_commission ELSE 0 END) as total_agent_commission'),
                DB::raw('COALESCE(trans.total_amount, 0) as total_amount'),
                DB::raw('ROUND(SUM(policies.premium) - COALESCE(trans.total_amount, 0)) as balance'),
                'agents.cut_and_pay' // Include cut_and_pay column
            )
            ->groupBy('policies.agent_id', 'agents.name', 'agents.cut_and_pay') // Group by cut_and_pay as well
            ->havingRaw('balance > 0')
            ->orderBy('balance', "desc")
            ->get();


        // Calculate sum for each column
        $totalPremium = $policy->sum('total_premium');
        $totalAmount = $policy->sum('total_amount');
        $totalBalance = $policy->sum('balance') - $policy->sum('total_agent_commission');

        $agentData = Agent::get();
        return view('admin.agent_pandding_blance', compact('policy', 'agentData', 'totalPremium', 'totalAmount', 'totalBalance'));
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
