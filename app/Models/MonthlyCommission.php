<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyCommission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id',
        'month',
        'year',
        'total_premium',
        'total_commission',
        'total_gst',
        'total_net_amount',
        'total_agent_amount_due',
        'policies_count',
        'payment_reference',
        'notes',
    ];

    protected $dates = [
        'deleted_at',
        'paid_date',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'total_premium' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'total_gst' => 'decimal:2',
        'total_net_amount' => 'decimal:2',
        'total_agent_amount_due' => 'decimal:2',
    ];

    /**
     * Get the agent that owns the monthly commission.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Get the month name
     *
     * @return string
     */
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 10));
    }

    /**
     * Get formatted month and year
     *
     * @return string
     */
    public function getMonthYearAttribute(): string
    {
        return date('F Y', mktime(0, 0, 0, $this->month, 10, $this->year));
    }
    
    /**
     * Scope a query to filter by specific month and year.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $month
     * @param int $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForMonthYear($query, int $month, int $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }
    
    /**
     * Scope a query to filter by current month.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentMonth($query)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return $query->where('month', $currentMonth)
                    ->where('year', $currentYear);
    }
    
    /**
     * Scope a query to filter by last month.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastMonth($query)
    {
        $lastMonth = now()->subMonth();
        
        return $query->where('month', $lastMonth->month)
                    ->where('year', $lastMonth->year);
    }
}