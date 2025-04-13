<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount_paid',
        'amount_remaining',
        'payment_method',
        'transaction_id',
        'notes',
        'payment_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
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
     * Get the agent who made this payment
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who created this payment
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated this payment
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this payment
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Set the payment date
     */
    public function setPaymentDateAttribute($value)
    {
        $this->attributes['payment_date'] = $value instanceof Carbon ? $value : Carbon::parse($value);
    }

    /**
     * Scope a query to only include payments from a specific agent
     */
    public function scopeFromAgent($query, $agentId)
    {
        return $query->where('user_id', $agentId);
    }

    /**
     * Scope a query to only include payments within a date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [Carbon::parse($startDate), Carbon::parse($endDate)]);
    }

    /**
     * Scope a query to only include payments from the current month
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('payment_date', Carbon::now()->month)
                     ->whereYear('payment_date', Carbon::now()->year);
    }
}
