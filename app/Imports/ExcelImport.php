<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Policy;
use App\Models\AgentCode;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExcelImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    protected $importDate;
    protected $rowsProcessed = 0;
    protected $rowsCreated = 0;
    protected $rowsUpdated = 0;
    protected $rowsSkipped = 0;
    protected $errorRows = [];

    public function __construct($importDate)
    {
        $this->importDate = $importDate;
    }

    public function model(array $row)
    {
        // Process each row individually to avoid batch issues with IDs
        try {
            $this->rowsProcessed++;

            // Skip rows with empty policy number
            if (empty($row['policy_no'])) {
                $this->rowsSkipped++;
                return null;
            }

            // Check if policy already exists by policy_no
            $existingRecord = Policy::where('policy_no', $row['policy_no'])->first();
            $isNewRecord = !$existingRecord;

            // If not found, create new policy
            if (!$existingRecord) {
                $existingRecord = new Policy();
                $existingRecord->policy_no = $row['policy_no'];
            }

            // Get commission code details from AgentCode model
            $commissionDetails = $this->getCommissionDetails($row['commission_code']);
            if (!$commissionDetails) {
                $this->errorRows[] = "Row with policy no {$row['policy_no']}: Invalid commission code";
                $this->rowsSkipped++;
                return null;
            }

            // Convert date first to avoid issues later in the process
            $policyStartDate = !empty($this->importDate)
                ? Carbon::parse($this->importDate)
                : Carbon::now();

            $existingRecord->policy_start_date = $policyStartDate;
            $existingRecord->policy_end_date = $policyStartDate->copy()->addYear();

            // Set customer name from Excel
            $existingRecord->customername = $row['customername'] ?? null;

            // Calculate financial values
            $premium = isset($row['premium']) ? (float)$row['premium'] : null;
            $gst_percentage = $commissionDetails->gst; // Assuming this is the percentage value (e.g., 18 for 18%)
            
            $net_amount = $premium && $gst_percentage
            ? round($premium - ($premium * $gst_percentage / 100), 2)
            : null;
            
            $gst_amount = $premium && $net_amount
                ? round($premium - $net_amount, 2)
                : null;

            // Get discount and payout from commission details
            $discount = $commissionDetails->discount ?? null;
            $effectiveDiscount = $commissionDetails->payout ?? null;
            $payout = ($net_amount && $effectiveDiscount) ? round($net_amount * $effectiveDiscount / 100, 2) : null;

            // Get policy type from insurance product related to commission code
            $policy_type = $commissionDetails->insurance_product_id;
            $agent_commission = getCommission($commissionDetails, $net_amount);


            // Process payment_by field from commission details
            $payment_by = $commissionDetails->payment_type;

            // Calculate agent_amount_due for pay_later policies
            // $agent_amount_due = null;
            // if (in_array($payment_by, ['pay_later'])) {
            //     $agent_amount_due = $premium ?? 0;
            // }

            // if (in_array($payment_by, ['pay_later_with_adjustment'])) {
            //     $agent_amount_due = ($premium - $agent_commission) ?? 0;
            // }

            // Update model fields
            $existingRecord->payment_by = $payment_by;
            $existingRecord->company_id = $commissionDetails->insurance_company_id;
            $existingRecord->discount = $discount;
            $existingRecord->agent_id = $commissionDetails->user_id;
            $existingRecord->premium = $premium;
            $existingRecord->gst = $premium && $commissionDetails->gst
                ? $gst_amount
                : null;
            $existingRecord->agent_commission = $agent_commission;
            $existingRecord->net_amount = $net_amount;
            $existingRecord->payout = $payout;
            $existingRecord->policy_type = $policy_type;
            $existingRecord->status = 'Unpaid'; 

            // Set agent_amount_due for pay_later policies
            // if ($agent_amount_due !== null) {
            //     $existingRecord->agent_amount_due = $agent_amount_due;

            //     // Initialize agent_amount_paid if it's a new record
            //     if ($isNewRecord) {
            //         $existingRecord->agent_amount_paid = 0;
            //     }
            // }

            // Get insurance company name if needed
            // $existingRecord->insurance_company = $this->getCompanyName($commissionDetails->insurance_company_id);

            // Default value for is_agent_commission_paid
           // $existingRecord->settled_for_previous_month =$commissionDetails->commission_settlement;

            // Save the record
            $existingRecord->save();

            // Track statistics
            if ($isNewRecord) {
                $this->rowsCreated++;
            } else {
                $this->rowsUpdated++;
            }

            return null; // Return null to prevent batch insertion

        } catch (\Exception $e) {
            $policyNo = isset($row['policy_no']) ? $row['policy_no'] : 'unknown';
            $this->errorRows[] = "Row with policy no {$policyNo}: " . $e->getMessage();
            $this->rowsSkipped++;
            Log::error("Error processing row: " . json_encode($row) . " | Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get commission details from AgentCode model
     */
    protected function getCommissionDetails($commission_code)
    {
        return AgentCode::where('code', $commission_code)->first();
    }








    /**
     * Get company name from company ID
     */
    protected function getCompanyName($company_id)
    {
        return DB::table('insurance_companies')->where('id', $company_id)->value('name');
    }

    /**
     * Get import statistics
     */
    public function getImportStats()
    {
        return [
            'processed' => $this->rowsProcessed,
            'created' => $this->rowsCreated,
            'updated' => $this->rowsUpdated,
            'skipped' => $this->rowsSkipped,
            'errors' => $this->errorRows
        ];
    }

    /**
     * Define validation rules based on the requirements
     */
    public function rules(): array
    {
        return [
            // Required fields
            'policy_no' => 'required',
            'customername' => 'required|string',

            // Commission code validation - required and must exist
            'commission_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $agentExists = AgentCode::where('code', $value)->exists();
                    if (!$agentExists) {
                        $fail("The specified commission code '{$value}' does not exist.");
                    }
                }
            ],

            // Premium validation
            'premium' => 'required|numeric|gt:0',
        ];
    }

    /**
     * Custom validation error messages
     */
    public function customValidationMessages()
    {
        return [
            'policy_no.required' => 'Policy number is required.',
            'customername.required' => 'Customer name is required.',
            'commission_code.required' => 'Commission code is required.',
            'premium.required' => 'Premium amount is required.',
            'premium.numeric' => 'Premium must be a number.',
            'premium.gt' => 'Premium must be greater than zero.',
        ];
    }

    /**
     * Define batch size for improved performance
     * Using smaller batch size to avoid issues
     */
    public function batchSize(): int
    {
        return 1; // Process one at a time to avoid batch insertion issues
    }

    /**
     * Define chunk size for memory efficiency
     */
    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Define heading row
     */
    public function headingRow(): int
    {
        return 1;
    }
}
