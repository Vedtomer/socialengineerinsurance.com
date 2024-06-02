<?php
use App\Models\Commission;
use App\Models\Company;

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
                return '';
            }
            $segment++;
        }
        return 'true';
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