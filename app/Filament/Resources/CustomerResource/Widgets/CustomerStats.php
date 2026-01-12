<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStats extends BaseWidget
{
    protected function getStats(): array
    {
        $analytics = getCustomerAnalytics();

        return [
            Stat::make('Total Customers', $analytics['totalCustomers'])
                ->description("App Active: {$analytics['totalAppActiveUsers']} | Inactive: " . ($analytics['totalCustomers'] - $analytics['totalAppActiveUsers']))
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Policies', $analytics['totalPolicies'])
                ->description('All customer policies')
                ->color('info')
                ->chart([3, 5, 7, 8, 5, 7, 7, 9]),

            Stat::make('Active Policies', $analytics['totalActivePolicies'])
                ->description('Currently active')
                ->color('success')
                ->chart([5, 7, 8, 9, 8, 9, 10, 11]),

            Stat::make('Expired Policies', $analytics['totalExpiredPolicies'])
                ->description('Needs renewal')
                ->color('danger')
                ->chart([2, 1, 1, 0, 1, 0, 0, 0]),
        ];
    }
}
