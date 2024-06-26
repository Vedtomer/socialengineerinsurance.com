<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceProduct extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'status'];

    public function customer_policies()
    {
        return $this->hasMany(CustomerPolicy::class, 'product_id');
    }

    public function getIconAttribute($value)
    {
        if ($value) {
            return asset($value); // Assuming $value is stored as 'icon/filename.ext' in the database
        }
        return null;
    }
}
