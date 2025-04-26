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
        // Get filter parameters
        list($agent_id, $start_date, $end_date) = $this->prepareDashboardData($request);

        // Build query with joins and calculated fields
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

        // Apply agent filter if specified
        if (!empty($agent_id)) {
            $query->where('agent_id', $agent_id);
        }

        // Execute query
        $data = $query->get();

        // Get all agents for filter dropdown
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
        foreach (
            [
                'total_premium',
                'total_commission',
                'total_net_amount',
                'total_gst',
                'total_payout',
                'total_amount_due_agents',
                'total_amount_paid_agents',
                'pending_amount_due'
            ] as $key
        ) {
            $analytics[$key] = number_format($analytics[$key], 2);
        }

        return view('admin.policy_list', [
            'data' => $data,
            'agentData' => $agentData,
            'analytics' => $analytics,
            'currentStartDate' => $start_date,
            'currentEndDate' => $end_date,
            'currentAgentId' => $agent_id
        ]);
    }

    /**
     * Helper function to prepare dashboard date filters
     */
    private function prepareDashboardData(Request $request)
    {
        // Get agent filter
        $agent_id = $request->input('agent_id', null);

        // Process date filters with defaults
        $start_date = $request->input('start_date', date('Y-m-01')); // Default to first day of current month
        $end_date = $request->input('end_date', date('Y-m-t'));     // Default to last day of current month

        return [$agent_id, $start_date, $end_date];
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
}
