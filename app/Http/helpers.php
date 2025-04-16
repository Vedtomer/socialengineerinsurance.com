<?php

use App\Models\Commission;
use App\Models\Company;
use App\Models\CustomerPolicy;
use App\Models\InsuranceCompany;
use App\Models\InsuranceProduct;
use App\Models\User;
use App\Models\Policy;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;
use Symfony\Component\HttpFoundation\Request;

if (!function_exists('classActivePath')) {
    function classActivePath($path)
    {
        $path = explode('.', $path);
        $segment = 2;
        foreach ($path as $p) {
            if ((request()->segment($segment) == $p) == false) {
                return '';
            }
            $segment++;
        }
        return ' active';
    }
}

if (!function_exists('ariaExpanded')) {
    function ariaExpanded($path)
    {
        $path = explode('.', $path);
        $segment = 2;
        foreach ($path as $p) {
            if ((request()->segment($segment) == $p) == false) {
                return false;
            }
            $segment++;
        }
        return true;
    }
}


if (!function_exists('getCommission')) {

    function getCommission($commissionDetails, $premium)
    {

        $commissionType = $commissionDetails->commission_type ?? 'percentage';
        $commissionValue = $commissionDetails->commission ?? 0;

        if ($commissionType === 'percentage') {
            return round($premium * $commissionValue / 100, 2);
        } else {
            return $commissionValue;
        }
    }
}

if (!function_exists('getAgentId')) {
    function getAgentId($commission_code)
    {
        if (!empty($commission_code)) {
            $commission = Commission::where('commission_code', $commission_code)->first();
            if ($commission && !empty($commission->agent_id)) {
                return $commission->agent_id;
            }
        }
        return null;
    }
}



if (!function_exists('getCompanyId')) {
    function getCompanyId($insurance_company)
    {
        if (!empty($insurance_company)) {
            $code = trim($insurance_company);
            $commission = InsuranceCompany::where('slug', $code)->first();
            if ($commission && !empty($commission->id)) {
                return $commission->id;
            }
        }
        return null;
    }
}

if (!function_exists('getAgents')) {
    function getAgents()
    {
        return $agentData = User::role('agent')->orderBy('name', 'asc')->get();
    }
}

if (!function_exists('getCustomers')) {
    function getCustomers()
    {
        return $agentData = User::role('customer')->orderBy('name', 'asc')->get();
    }
}

if (!function_exists('getInsuranceProducts')) {
    function getInsuranceProducts()
    {
        return $agentData = InsuranceProduct::where('status', 1)->orderBy('name', 'asc')->get();
    }
}




if (!function_exists('getPolicy')) {
    function getPolicy()
    {
        return  $Policy = Policy::all();
    }
}


if (!function_exists('getMonthsFromAprilToCurrent')) {
    function getMonthsFromAprilToCurrent()
    {
        $current_selection = now()->subYear()->format('F, Y');
        $years = $months = [];
        $current_year = now()->format('Y');
        $years[] = $current_year;
        $current_month_number = now()->format('m');
        $months = array_merge($months, array_map(function ($month_number) use ($current_year) {
            $target_date = now()->parse($current_year . '-' . sprintf('%02d', $month_number) . '-01');
            return [
                'name' => $target_date->format('F, Y'),
                'value' => strtolower($target_date->format('Y-m-d'))
            ];
        }, range(1, $current_month_number)));
        if ($current_month_number <= 4) {
            $last_year = now()->subYear()->format('Y');
            $years[] = $last_year;
            $months = array_merge($months, array_map(function ($month_number) use ($last_year) {
                $target_date = now()->parse($last_year . '-' . sprintf('%02d', $month_number) . '-01');
                return [
                    'name' => $target_date->format('F, Y'),
                    'value' => strtolower($target_date->format('Y-m-d'))
                ];
            }, range(12, 4)));
        }
        return compact('years', 'months', 'current_selection');
    }
}



if (!function_exists('prepareDashboardData')) {
    function prepareDashboardData(Request $request)
    {
        $agent_id = $request->input('agent_id', "");
        $date = $request->input('date', "");
        $date_range = $request->input('date_range', "");

        $start_date = Carbon::now()->firstOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();

        if ($date_range) {
            // Check if the date_range contains a "to" separator
            if (strpos($date_range, ' to ') !== false) {
                // Split the date range string into start and end dates
                $dates = explode(' to ', $date_range);
                if (count($dates) == 2) {
                    $start_date = Carbon::parse($dates[0])->toDateString();
                    $end_date = Carbon::parse($dates[1])->toDateString();
                }
            } else {
                // If there's no "to" separator, set both start and end dates to the single date
                $start_date = Carbon::parse($date_range)->toDateString();
                $end_date = $start_date;
            }
        } elseif (!empty($date)) {
            if (strlen($date) == 4) {
                $start_date = Carbon::parse($date . '-01-01')->toDateString();
                $end_date = Carbon::parse($date . '-12-31')->endOfYear()->toDateString();
            } else {
                $start_date = Carbon::parse($date)->toDateString();
                $end_date = Carbon::parse($date)->endOfMonth()->toDateString();
            }
        }

        return [$agent_id, $start_date, $end_date];
    }
}


function prepareApiParameter(Request $request)
{
    $agent_id = auth()->guard('api')->id();
    $start_date_input = $request->input('start_date', "");
    $end_date_input = $request->input('end_date', "");

    try {
        if ($start_date_input && $end_date_input) {
            $start_date = Carbon::createFromFormat('d-m-Y', $start_date_input)->toDateString();
            $end_date = Carbon::createFromFormat('d-m-Y', $end_date_input)->toDateString();
        } else {
            $start_date = Carbon::now()->firstOfMonth()->toDateString();
            $end_date = Carbon::now()->toDateString();
        }
    } catch (\Exception $e) {
        Log::error('Error parsing date: ' . $e->getMessage());
        $start_date = Carbon::now()->firstOfMonth()->toDateString();
        $end_date = Carbon::now()->toDateString();
    }

    Log::info('Parsed start_date: ' . $start_date);
    Log::info('Parsed end_date: ' . $end_date);

    return [$agent_id, $start_date, $end_date];
}

function getCustomerAnalytics()
{
    $customers = User::role('customer')
        ->orderBy("id", "desc")
        ->withCount('customerPolicies')
        ->get();

    $customerIds = $customers->pluck('id');

    $currentDate = now();

    return [
        'totalCustomers' => $customers->count(),
        'totalAppActiveUsers' => UserActivity::whereIn('user_id', $customerIds)->distinct('user_id')->count(),
        'totalPolicies' => CustomerPolicy::whereIn('user_id', $customerIds)->count(),
        'totalActivePolicies' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'active')
            ->count(),
        'totalExpiredPolicies' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'expired')
            ->count(),
    ];
}



function getCustomerPolicyAnalytics()
{
    $customers = User::role('customer')
        ->orderBy("id", "desc")
        ->withCount('customerPolicies')
        ->get();

    $customerIds = $customers->pluck('id');

    $currentDate = now();
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    $next7Days = Carbon::now()->addDays(7);

    return [
        'totalCustomers' => $customers->count(),
        'totalPolicies' => CustomerPolicy::whereIn('user_id', $customerIds)->count(),

        'activePoliciesCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'active') // Assuming 'active' is a status value
            ->count(),

        'expiredPoliciesCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'expired') // Assuming 'expired' is a status value
            ->count(),

        'cancelledPoliciesCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'cancelled') // Assuming 'cancelled' is a status value
            ->count(),

        'pendingPoliciesCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('status', 'pending') // Assuming 'pending' is a status value
            ->count(),

        'policiesExpiringThisMonthCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->whereBetween('policy_end_date', [$startOfMonth, $endOfMonth])
            ->count(),

        'policiesExpiringIn7DaysCount' => CustomerPolicy::whereIn('user_id', $customerIds)
            ->where('policy_end_date', '<=', $next7Days)
            ->where('policy_end_date', '>=', $currentDate)
            ->count(),
        'totalAppActiveUsers' => UserActivity::whereIn('user_id', $customerIds)->distinct('user_id')->count(),
    ];
}
