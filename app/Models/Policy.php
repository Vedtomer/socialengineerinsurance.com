<?php

namespace App\Models;


use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use function PHPUnit\Framework\returnSelf;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Policy extends Model
{
    use HasFactory;
    protected $fillable = [
        'policy_no',
        'policy_start_date',
        'policy_end_date',
        'customername',
        'insurance_company',
        'agent_id',
        'premium',
        'gst',
        'agent_commission',
        'net_amount',
        'payment_by',
        'company_id'
        // Add other attributes here if needed
    ];
    protected $appends = ['policy_link'];
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function getPolicyLinkAttribute()
    {

        $data = ('/policies') . "/" . $this->policy_no . '.pdf';

        if (Storage::disk('public')->exists($data)) {
            return asset('/storage/policies') . "/" . $this->policy_no . '.pdf';
        } else {
            return "";
        }
    }
}
