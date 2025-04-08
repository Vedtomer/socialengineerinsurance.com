<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
        'agent_id',
        'amount_paid',
        'amount_remaining',
        'payment_method',
        'transaction_id',
        'notes',
        'payment_date',
        'status',
        'created_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'datetime',
        'amount_paid' => 'decimal:2',
        'amount_remaining' => 'decimal:2'
    ];

    /**
     * Get the policy that this payment belongs to
     */
    // public function policy()
    // {
    //     return $this->belongsTo(Policy::class);
    // }

    /**
     * Get the agent who made this payment
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the user who recorded this payment
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Set the payment date
     */
    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    /**
     * Scope a query to only include payments for a specific policy
     */
    // public function scopeForPolicy($query, $policyId)
    // {
    //     return $query->where('policy_id', $policyId);
    // }

    /**
     * Scope a query to only include payments from a specific agent
     */
    public function scopeFromAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope a query to only include payments within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [Carbon::parse($startDate), Carbon::parse($endDate)]);
    }

    /**
     * Update policy payment status after saving a payment
     */
    // protected static function booted()
    // {
    //     static::saved(function ($payment) {
    //         $policy = $payment->policy;

    //         // Update the agent_amount_paid on the policy
    //         $totalPaid = self::where('policy_id', $policy->id)->sum('amount_paid');
    //         $policy->agent_amount_paid = $totalPaid;

    //         // Check if fully paid
    //         if ($policy->agent_amount_due <= $policy->agent_amount_paid) {
    //             // If agent has fully paid, update the status if it was pay_later related
    //             if (in_array($policy->payment_by, ['pay_later', 'pay_later_with_adjustment'])) {
    //                 $policy->payment_by = 'agent_full_payment';
    //             }
    //         }

    //         $policy->save();
    //     });
    // }

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'policy_transaction')
            ->withPivot('amount')
            ->withTimestamps();
    }
}
