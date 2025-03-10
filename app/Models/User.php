<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Commission;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

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
        'status',
        'agent_code',
        'cut_and_pay',
        'otp',
        'otp_sent_at',
        'username'
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

    public function getprofileImageAttribute($value)
    {
        $data = ('/profile') . "/" . $value;
        if (Storage::disk('public')->has($data)) {
            return asset('/storage/profile') . "/" . $value;
        } else {
            return "";
        }
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_sent_at' => 'datetime',
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


            $royalData = Policy::whereBetween('policy_start_date', [$startDate, $endDate])
                ->where('agent_id', $agent_id)
                ->select('policy_no', 'policy_start_date', 'policy_end_date', 'customername', 'premium', 'agent_commission', 'insurance_company')
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

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null; // see the note above in Gate::before about why null must be returned here.
    }

    public function customerPolicies()
    {
        return $this->hasMany(CustomerPolicy::class, 'user_id');
    }
}
