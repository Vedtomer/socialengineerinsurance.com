<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        // Get data for filters
        $companies = InsuranceCompany::orderBy('name')->get();
        $agents = User::role('agent')->get();
        $insuranceProducts = InsuranceProduct::orderBy('name')->get();
        $paymentTypes = Policy::getPaymentTypes();

        return view('admin.reports.index', compact('companies', 'agents', 'insuranceProducts', 'paymentTypes'));
    }

    public function downloadPolicyReport(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
                'company_id' => 'nullable|exists:companies,id',
                'agent_id' => 'nullable|exists:users,id',
                'policy_type' => 'nullable|exists:insurance_products,id',
                'payment_by' => 'nullable|string',
                'insurance_company' => 'nullable|string',
            ]);

            // Start query
            $query = Policy::query();

            // Apply filters
            if ($request->from_date) {
                $query->whereDate('policy_start_date', '>=', $request->from_date);
            }
            if ($request->to_date) {
                $query->whereDate('policy_start_date', '<=', $request->to_date);
            }
            if ($request->company_id) {
                $query->where('company_id', $request->company_id);
            }
            if ($request->agent_id) {
                $query->where('agent_id', $request->agent_id);
            }
            if ($request->policy_type) {
                $query->where('policy_type', $request->policy_type);
            }
            if ($request->payment_by) {
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
            return redirect()->back()->with('error', 'Something went wrong while generating the report.');
        }
    }
}
