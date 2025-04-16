<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentMonthlySettlement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id',
        'year',
        'month',
        'total_commission',
        'total_premium_due',
        'amount_paid',
        'pending_amount',
        'carry_forward_due',
        'final_amount_due',
        'notes',
    ];

    protected $casts = [
        'total_commission' => 'decimal:2',
        'total_premium_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'carry_forward_due' => 'decimal:2',
        'final_amount_due' => 'decimal:2',
    ];

    /**
     * Get the agent related to this settlement.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get Carbon date object for the settlement period.
     */
    public function getSettlementDateAttribute(): Carbon
    {
        return Carbon::createFromDate($this->year, $this->month, 1);
    }

    /**
     * Get related policy transactions for this settlement period.
     */
    public function policies()
    {
        $startDate = $this->settlement_date->startOfMonth();
        $endDate = $this->settlement_date->copy()->endOfMonth();

        return Policy::where('agent_id', $this->agent_id)
            ->whereBetween('policy_start_date', [$startDate, $endDate]);
    }

    /**
     * Get related account transactions for this settlement period.
     */
    public function accountTransactions()
    {
        $startDate = $this->settlement_date->startOfMonth();
        $endDate = $this->settlement_date->copy()->endOfMonth();

        return Account::where('user_id', $this->agent_id)
            ->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include settlements for a specific agent.
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope a query to get the current month's settlement.
     */
    public function scopeCurrentMonth($query, $date = null)
    {
        $date = $date ?? now();

        return $query->where('year', $date->year)
                     ->where('month', $date->month);
    }

    /**
     * Scope a query to get the previous month's settlement.
     */
    public function scopePreviousMonth($query, $date = null)
    {
        $date = $date ?? now()->subMonth();

        return $query->where('year', $date->year)
                     ->where('month', $date->month);
    }

    /**
     * Scope to get settlements with outstanding dues.
     */
    public function scopeOutstanding($query)
    {
        return $query->where('final_amount_due', '>', 0);
    }

    /**
     * Scope to get settlements between two months.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return $query->where(function ($q) use ($start, $end) {
            $q->where(function ($q) use ($start) {
                $q->where('year', '>', $start->year)
                  ->orWhere(function ($q) use ($start) {
                      $q->where('year', $start->year)
                        ->where('month', '>=', $start->month);
                  });
            })->where(function ($q) use ($end) {
                $q->where('year', '<', $end->year)
                  ->orWhere(function ($q) use ($end) {
                      $q->where('year', $end->year)
                        ->where('month', '<=', $end->month);
                  });
            });
        });
    }
}
