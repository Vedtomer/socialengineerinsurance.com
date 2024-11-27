<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessageLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mobile_number',
        'message_type',
        'policy_count',
        'total_commission',
        'days_since_last_policy',
        'request_payload',
        'response_body',
        'is_successful',
        'error_message',
        'sent_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request_payload' => 'array',
        'response_body' => 'array',
        'is_successful' => 'boolean',
        'sent_at' => 'datetime'
    ];

    /**
     * Get the user associated with the message log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}