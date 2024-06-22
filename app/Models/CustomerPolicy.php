<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerPolicy extends Model
{
    use HasFactory;

    protected $appends = ['policy_link', 'user_name']; // Added 'user_name' to the appends array
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

    // Accessor to get the policy link
    public function getPolicyLinkAttribute()
    {
        $data = ('/customer_policies') . "/" . $this->policy_no . '.pdf';

        if (Storage::disk('public')->exists($data)) {
            return asset('/storage/customer_policies') . "/" . $this->policy_no . '.pdf';
        } else {
            return "";
        }
    }

    // Accessor to get the user's name
    public function getUserNameAttribute()
    {
        return $this->user->name; // Assuming 'user' is the relationship method
    }

    // Relationship to fetch the associated user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
