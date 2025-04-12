<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InsuranceCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'image',
        'status'
    ];

    /**
     * Get the formatted image URL
     *
     * @param  string  $value
     * @return string
     */
    public function getImageAttribute($value)
    {
        if (!$value) {
            return "";
        }

        $path = '/company/' . $value;

        if (Storage::disk('public')->exists($path)) {
            return asset('/storage/company/' . $value);
        } else {
            return "";
        }
    }
    
    /**
     * Insurance products associated with this company
     */
    public function insuranceProducts()
    {
        return $this->hasMany(InsuranceProduct::class, 'insurance_company_id');
    }
    
    /**
     * Agent codes associated with this company
     */
    public function agentCodes()
    {
        return $this->hasMany(AgentCode::class, 'insurance_company_id');
    }
}