<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Policy;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExcelImport implements ToModel, WithHeadingRow
{
    protected $importDate;

    public function __construct($importDate)
    {
        $this->importDate = $importDate;
    }

    public function model(array $row)
    {
        $existingRecord = Policy::firstOrNew(['policy_no' => $row['policy_no']]);
    
        Log::info($row);
    
        $existingRecord->policy_start_date = !empty($this->importDate)
            ? Carbon::parse($this->importDate)
            : $this->parseDate($row['policy_start_date']);
    
        $existingRecord->policy_end_date = $existingRecord->policy_start_date->copy()->addYear();
    
        $premium = $row['premium'] ?? null;
        $net_amount = $premium ? $premium * 0.8475 : null;
        $discount = $row['discount'] ?? null;
        $effectiveDiscount = $row['payout'] ?? null;
        $payout = ($net_amount && $effectiveDiscount) ? round($net_amount * $effectiveDiscount / 100, 2) : null;
        
        // Get policy type from row
        $policy_type = isset($row['policy_type']) ? strtolower(trim($row['policy_type'])) : null;
        
        // Calculate agent commission based on policy type
        $agent_commission = null;
        if (isset($row['commission_code'])) {
            if ($policy_type === 'two-wheeler') {
                $agent_commission = 0;
            } else {
                $agent_commission = getCommission($row['commission_code'], $premium);
            }
        }
    
        $existingRecord->fill([
            'payment_by' => isset($row['payment_by']) ? strtoupper(trim($row['payment_by'])) : null,
            'company_id' => isset($row['insurance_company']) ? getCompanyId($row['insurance_company']) : null,
            'customername' => $row['customername'] ?? null,
            'discount' => $discount,
            'agent_id' => isset($row['commission_code']) ? getAgentId($row['commission_code']) : null,
            'premium' => $premium,
            'gst' => $premium ? $premium * 0.1525 : null,
            'agent_commission' => $agent_commission,
            'net_amount' => $net_amount,
            'payout' => $payout,
            'policy_type' => $policy_type,
        ]);
    
        $existingRecord->save();
    
        return $existingRecord;
    }

    protected function parseDate($value)
    {
        if (is_numeric($value)) {
            $excelBaseDate = strtotime('1899-12-30');
            $dateInSeconds = ($value) * 24 * 60 * 60;
            $unixTimestamp = $excelBaseDate + $dateInSeconds;
            return Carbon::createFromTimestamp($unixTimestamp)->startOfDay();
        } else {
            return Carbon::createFromFormat('d/m/Y', $value)->startOfDay();
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
