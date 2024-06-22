<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPolicy extends Model
{
    use HasFactory;

    protected $table = 'customer_policies';

    protected $fillable = [
        'policy_no',
        'policy_start_date',
        'policy_end_date',
        'user_id',
        'status',
        'net_amount',
        'gst',
        'premium',
        'insurance_company',
        'policy_type',
    ];

    protected $casts = [
        'policy_start_date' => 'date',
        'policy_end_date' => 'date',
    ];
}
