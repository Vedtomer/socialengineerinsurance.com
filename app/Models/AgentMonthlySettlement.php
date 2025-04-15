<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgentMonthlySettlement extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id',
        'settlement_month',
        'total_commission',
        'total_premium_due',
        'pay_later_amount',
        'pay_later_with_adjustment_amount',
        'amount_paid',
        'pending_amount',
        'previous_month_commission',
        'adjusted_commission',
        'carry_forward_due',
        'final_amount_due',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settlement_month' => 'date',
        'total_commission' => 'decimal:2',
        'total_premium_due' => 'decimal:2',
        'pay_later_amount' => 'decimal:2',
        'pay_later_with_adjustment_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'previous_month_commission' => 'decimal:2',
        'adjusted_commission' => 'decimal:2',
        'carry_forward_due' => 'decimal:2',
        'final_amount_due' => 'decimal:2',
    ];

    /**
     * Get the agent related to this settlement
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get related policy transactions for this settlement period
     */
    public function policies()
    {
        $startDate = $this->settlement_month->startOfMonth();
        $endDate = $this->settlement_month->copy()->endOfMonth();
        
        return Policy::where('agent_id', $this->agent_id)
            ->whereBetween('policy_start_date', [$startDate, $endDate]);
    }

    /**
     * Get related account transactions for this settlement period
     */
    public function accountTransactions()
    {
        $startDate = $this->settlement_month->startOfMonth();
        $endDate = $this->settlement_month->copy()->endOfMonth();
        
        return Account::where('user_id', $this->agent_id)
            ->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include settlements for a specific agent
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope a query to get the previous month's settlement
     */
    public function scopePreviousMonth($query, $currentMonth = null)
    {
        $currentMonth = $currentMonth ?? Carbon::now();
        $previousMonth = Carbon::parse($currentMonth)->subMonth();
        
        return $query->whereYear('settlement_month', $previousMonth->year)
                     ->whereMonth('settlement_month', $previousMonth->month);
    }

    /**
     * Scope a query to get the current month's settlement
     */
    public function scopeCurrentMonth($query, $date = null)
    {
        $date = $date ?? Carbon::now();
        
        return $query->whereYear('settlement_month', $date->year)
                     ->whereMonth('settlement_month', $date->month);
    }
    
    /**
     * Get all outstanding settlements for an agent
     */
    public function scopeOutstanding($query)
    {
        return $query->where('final_amount_due', '>', 0);
    }
    
    /**
     * Get settlements with a specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('settlement_month', [
            Carbon::parse($startDate)->startOfMonth(),
            Carbon::parse($endDate)->endOfMonth()
        ]);
    }
}