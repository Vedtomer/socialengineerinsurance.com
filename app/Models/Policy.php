<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'policy_no',
        'policy_start_date',
        'policy_end_date',
        'customername',
        'insurance_company',
        'agent_id',
        'premium',
        'agent_amount_due',
        'gst',
        'agent_commission',
        'net_amount',
        'payment_by',
        'company_id',
        'discount',
        'payout',
        'policy_type',
        'settled_for_previous_month'
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['policy_link'];

    const PAYMENT_BY_AGENT = 'agent_full_payment';
   
    const PAYMENT_BY_COMMISSION_DEDUCTED = 'commission_deducted';
    const PAYMENT_BY_PAY_LATER_ADJUSTED = 'pay_later_with_adjustment';
    const PAYMENT_BY_PAY_LATER = 'pay_later';

    public static function getPaymentTypes()
    {
        return [
            self::PAYMENT_BY_AGENT => 'Agent Paid',
            // self::PAYMENT_BY_COMPANY => 'SEI Paid',
            self::PAYMENT_BY_COMMISSION_DEDUCTED => 'Commission Deducted',
            self::PAYMENT_BY_PAY_LATER_ADJUSTED => 'Pay Later (Adjustment)',
            self::PAYMENT_BY_PAY_LATER => 'Pay Later',
        ];
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id')->withDefault([
            'name' => 'No Agent Assigned',
            
        ]);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id')->withDefault([
            'name' => 'No Company Assigned',
            
        ]);
    }

    public function insuranceProduct(): BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class, 'policy_type')->withDefault([
            'name' => 'N/A',
            
        ]);
    }

    

    public function getPolicyLinkAttribute()
    {

        $data = ('/policies') . "/" . $this->policy_no . '.pdf';

        if (Storage::disk('public')->exists($data)) {
            return asset('/storage/policies') . "/" . $this->policy_no . '.pdf';
        } else {
            return "";
        }
    }

    public function getAgentNameAttribute()
    {
        $agent = $this->agent;
        return $agent ? $agent->name : null;
    }


    

    
}

