<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerPolicy extends Model
{
    use HasFactory;

    protected $appends = ['policy_link', 'user_name', 'product_name'];
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
        'product_id'
    ];

    // Hide user and product relationships in JSON response
    protected $hidden = ['user', 'product'];

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
        return $this->user ? $this->user->name : null;
    }

    // Accessor to get the product's name
    public function getProductNameAttribute()
    {
        return $this->product ? $this->product->name : null;
    }

    // Relationship to fetch the associated user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer() // Define the relationship as 'customer'
    {
        return $this->belongsTo(User::class, 'user_id'); // Assuming 'user_id' is the foreign key in customer_policies table
    }

    // Relationship to fetch the associated product
    public function product()
    {
        return $this->belongsTo(InsuranceProduct::class, 'product_id');
    }
}
