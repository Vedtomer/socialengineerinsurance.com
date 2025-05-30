<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id',
        'insurance_product_id',
        'commission_type',
        'commission',
        'commission_code',
        'payment_type',
        'gst',
        'discount',
        'payout',
        'insurance_company_id',
        'commission_settlement',
    ];
    

    /**
     * Get the agent associated with the commission.
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the insurance product associated with the commission.
     */
    public function insuranceProduct()
    {
        return $this->belongsTo(InsuranceProduct::class);
    }
}