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
        return redirect()->back()->with('success', 'Data imported successfully!');
    }

    public function PolicyList(Request $request)
    {
        $start_date = $request->input('start_date', "") ===  "null"  ? "" : $request->input('start_date');
        $end_date = $request->input('end_date', "") ===  "null"  ? "" : $request->input('end_date');
        $agent_id = $request->input('agent_id', "") === "null" ? "" : $request->input('agent_id', "");


        $start_date = $start_date ? Carbon::parse($start_date)->startOfDay() : now()->startOfMonth();
        $end_date = $end_date ? Carbon::parse($end_date)->endOfDay() : now()->endOfDay();

        $query = Policy::with('agent')->whereBetween('policy_start_date', [$start_date, $end_date])->orderBy('id', 'desc');

        if (!empty($agent_id)) {
            $query->where('agent_id', $agent_id);
        }

        $data = $query->get();
        $agentData = User::role('agent')->get();

        return view('admin.policy_list', ['data' => $data, 'agentData' => $agentData]);
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
            ->orderBy('balance',"desc")
            ->get();


        // Calculate sum for each column
        $totalPremium = $policy->sum('total_premium');
        $totalAmount = $policy->sum('total_amount');
        $totalBalance = $policy->sum('balance')-$policy->sum('total_agent_commission');

        $agentData = Agent::get();
        return view('admin.agent_pandding_blance', compact('policy', 'agentData', 'totalPremium', 'totalAmount', 'totalBalance'));
    }
}
