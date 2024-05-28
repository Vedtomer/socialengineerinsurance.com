<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Commission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
class Agent extends Authenticatable implements MustVerifyEmail
{
    use  HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'state',
        'city',
        'address',
        'mobile_number',
        'commission',
        'status', 'agent_code','cut_and_pay'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'agent_id');
    }

    public function Policy(): HasMany
    {
        return $this->hasMany(Policy::class, 'agent_id');
    }

    public function Transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'agent_id');
    }

    public function getPoliciesCount($request = null)
    {
        try {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            if (empty($startDate)) {
                $startDate = Carbon::now()->firstOfMonth();
            } else {
                $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->startOfDay();
            }

            if (empty($endDate)) {
                $endDate = Carbon::now();
            } else {
                $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->endOfDay();
            }
            $agent_id =  auth()->guard('api')->user()->id;

            
          $royalData = Policy::
                whereBetween('policy_start_date', [$startDate, $endDate])
           ->where('agent_id', $agent_id)
            ->select('policy_no', 'policy_start_date', 'policy_end_date', 'customername', 'premium', 'agent_commission','insurance_company')
            ->get()
            ->append('policy_link');

            return response([
                'status' => true,
                'data' => $royalData,
                'message' => 'Policy listing'
            ]);
        } catch (\Exception $e) {

            Log::error('Exception: ' . $e->getMessage());

            return response()->json(['message' => $e->getMessage(), 'status' => false, 'data' => []], 500);
        }
    }


}

