<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Policy;
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

            // Convert date first to avoid issues later in the process
            $policyStartDate = !empty($this->importDate)
                ? Carbon::parse($this->importDate)
                : $this->parseDate($row['policy_start_date'] ?? null);

            if (!$policyStartDate) {
                $this->errorRows[] = "Row with policy no {$row['policy_no']}: Invalid date format";
                $this->rowsSkipped++;
                return null;
            }

            $existingRecord->policy_start_date = $policyStartDate;
            $existingRecord->policy_end_date = $policyStartDate->copy()->addYear();

            // Calculate financial values
            $premium = isset($row['premium']) ? (float)$row['premium'] : null;
            $net_amount = $premium ? round($premium * 0.8475, 2) : null;
            $discount = isset($row['discount']) ? (float)$row['discount'] : null;
            $effectiveDiscount = isset($row['payout']) ? (float)$row['payout'] : null;
            $payout = ($net_amount && $effectiveDiscount) ? round($net_amount * $effectiveDiscount / 100, 2) : null;

            // Process policy type
            $policy_type = isset($row['policy_type']) ? strtolower(trim($row['policy_type'])) : null;

            // Calculate agent commission
            $agent_commission = null;
            if (isset($row['commission_code']) && $premium) {
                if ($policy_type === 'two-wheeler') {
                    $agent_commission = 0;
                } else {
                    $agent_commission = getCommission($row['commission_code'], $premium);
                }
            }

            // Process payment_by field
            $payment_by = $this->validatePaymentBy($row['payment_by'] ?? null);
            
            // Calculate agent_amount_due for pay_later policies
            $agent_amount_due = null;
            if (in_array($payment_by, ['pay_later'])) {
                $agent_amount_due = $premium ?? 0;
            }

            if (in_array($payment_by, ['pay_later_with_adjustment'])) {
                $agent_amount_due = ($premium - $agent_commission ) ?? 0;
            }

            // if (in_array($payment_by, ['company_paid'])) {
            //     $agent_amount_due = $premium ?? 0;
            // }

            
            
            // Update model fields
            $existingRecord->payment_by = $payment_by;
            $existingRecord->company_id = isset($row['insurance_company']) ? getCompanyId($row['insurance_company']) : null;
            $existingRecord->customername = $row['customername'] ?? null;
            $existingRecord->discount = $discount;
            $existingRecord->agent_id = isset($row['commission_code']) ? getAgentId($row['commission_code']) : null;
            $existingRecord->premium = $premium;
            $existingRecord->gst = $premium ? round($premium * 0.1525, 2) : null;
            $existingRecord->agent_commission = $agent_commission;
            $existingRecord->net_amount = $net_amount;
            $existingRecord->payout = $payout;
            $existingRecord->policy_type = $policy_type;
            $existingRecord->status = $row['status'] ?? 'Unpaid';
            
            // Set agent_amount_due for pay_later policies
            if ($agent_amount_due !== null) {
                $existingRecord->agent_amount_due = $agent_amount_due;
                
                // Initialize agent_amount_paid if it's a new record
                if ($isNewRecord) {
                    $existingRecord->agent_amount_paid = 0;
                }
            }
            
            // If you have an insurance_company field in the policies table 
            if (isset($row['insurance_company'])) {
                $existingRecord->insurance_company = $row['insurance_company'];
            }
            
            // Set is_agent_commission_paid if it exists
            $existingRecord->is_agent_commission_paid = $row['is_agent_commission_paid'] ?? 0;

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
     * Parse date from Excel format
     */
    protected function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }
        
        try {
            if (is_numeric($value)) {
                // Handle Excel numeric date format
                $excelBaseDate = strtotime('1899-12-30');
                $dateInSeconds = ($value) * 24 * 60 * 60;
                $unixTimestamp = $excelBaseDate + $dateInSeconds;
                return Carbon::createFromTimestamp($unixTimestamp)->startOfDay();
            } elseif (is_string($value)) {
                // Try different date formats
                foreach (['d/m/Y', 'Y-m-d', 'm/d/Y'] as $format) {
                    try {
                        return Carbon::createFromFormat($format, $value)->startOfDay();
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error("Date parsing error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate payment methods
     */
    protected function validatePaymentBy($value)
    {
        if (empty($value)) {
            return null;
        }
        
        $allowedValues = [
            'agent_full_payment',
            // 'company_paid',
            'commission_deducted',
            'pay_later_with_adjustment',
            'pay_later'
        ];

        $value = strtolower(trim($value));

        return in_array($value, $allowedValues) ? $value : null;
    }

   

    

    

    /**
     * Define validation rules based on the requirements
     */
    public function rules(): array
    {
        return [
            // Required fields
            'policy_no' => 'required|string',
            'customername' => 'required|string',
            
            // Insurance company validation - required and must exist
            'insurance_company' => [
                'required',
                function ($attribute, $value, $fail) {
                    $companyExists = DB::table('companies')->where('slug', 'like', '%' . $value . '%')->exists();
                    if (!$companyExists) {
                        $fail("The specified insurance company '{$value}' does not exist.");
                    }
                }
            ],
            
            // Commission code validation - required and must exist
            'commission_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $agentExists = DB::table('commissions')->where('commission_code', $value)->exists();
                    if (!$agentExists) {
                        $fail("The specified commission code '{$value}' does not exist.");
                    }
                }
            ],
            
            // Payment by validation - must be in allowed values
            'payment_by' => [
                'required',
                Rule::in([
                    'agent_full_payment',
                    'company_paid',
                    'commission_deducted',
                    'pay_later_with_adjustment',
                    'pay_later'
                ])
            ],
            
            // Nullable fields
            'discount' => 'nullable|numeric',
            'payout' => 'nullable|numeric',
            
            // Policy type validation - nullable but if present, must be two-wheeler
            'policy_type' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== null && strtolower(trim($value)) !== 'two-wheeler') {
                        $fail("The policy type must be 'two-wheeler' if provided.");
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
            'insurance_company.required' => 'Insurance company is required.',
            'commission_code.required' => 'Commission code is required.',
            'payment_by.required' => 'Payment method is required.',
            'payment_by.in' => 'Payment method must be one of: agent_full_payment, company_paid, commission_deducted, pay_later_with_adjustment, pay_later.',
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