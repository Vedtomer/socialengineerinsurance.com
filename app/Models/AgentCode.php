<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'insurance_product_id',
        'commission_type',
        'commission',
        'code',
        'payment_type',
        'gst',
        'discount',
        'payout',
        'insurance_company_id',
        'commission_settlement'
    ];

    /**
     * Get the user that owns the agent code
     */
    public function agnet()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the insurance product associated with the agent code
     */
    public function insuranceProduct()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    /**
     * Get the insurance company associated with the agent code
     */
    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class, 'insurance_company_id');
    }
}

