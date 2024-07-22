<?php

use App\Models\Commission;
use App\Models\Company;
use App\Models\InsuranceProduct;
use App\Models\User;
use App\Models\Policy;
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

    function getCommission($commission_code, $premium)
    {

        if (!empty($commission_code) && !empty($premium)) {
            $commission = Commission::where('commission_code', $commission_code)->get();
            if ($commission->count() === 1) {
                $commission = $commission->first();
                if ($commission->commission_type === 'fixed') {
                    return $commission->commission;
                } elseif ($commission->commission_type === 'percentage') {
                    $percentageCommission = ($commission->commission / 100) * ($premium - $premium * 0.1525);
                    return $percentageCommission;
                }
            }
        }
        return null;
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
            $commission = Company::where('slug', $code)->first();
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
        $currentDate = now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        // Generate months list from April to the current month
        $months = [];
        for ($month = 4; $month <= $currentMonth; $month++) {
            $months[] = [
                'name' => Carbon::create()->month($month)->format('F'),
                'value' => $month,
            ];
        }

        return [
            'months' => $months,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth
        ];
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
        } elseif ($date == "year") {
            $start_date = Carbon::now()->startOfYear()->addMonths(3)->toDateString();
        } elseif (is_numeric($date)) {
            $month = intval($date);
            $start_date = Carbon::create(null, $month, 1)->toDateString();
            $end_date = Carbon::create(null, $month, 1)->endOfMonth()->toDateString();
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
