<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerPolicy extends Model
{
    use HasFactory;
    protected $appends = ['policy_link'];
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

    public function getPolicyLinkAttribute()
    {

        $data = ('/customer_policies') . "/" . $this->policy_no . '.pdf';

        if (Storage::disk('public')->exists($data)) {
            return asset('/storage/customer_policies') . "/" . $this->policy_no . '.pdf';
        } else {
            return "";
        }
    }
}
