<?php

namespace App\Filament\Resources\CustomerPolicyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerPolicyStats extends BaseWidget
{
    protected function getStats(): array
    {
        $analytics = getCustomerPolicyAnalytics();

        return [
            Stat::make('Customer Overview', $analytics['totalCustomers'])
                ->description("Policies: {$analytics['totalPolicies']} | App Users: " . ($analytics['totalAppActiveUsers'] ?? 'N/A'))
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]), // Dummy chart or calculate it

            Stat::make('Active Business', $analytics['activePoliciesCount'])
                ->description("Active Policies")
                ->color('success')
                ->chart([3, 5, 7, 8, 5, 7, 7, 9]),

            Stat::make('Attention Required', $analytics['expiredPoliciesCount'])
                ->description("Cancelled: {$analytics['cancelledPoliciesCount']}")
                ->color('danger')
                ->chart([9, 5, 1, 1, 2, 0, 1, 0]),

            Stat::make('Upcoming Expiry', $analytics['policiesExpiringThisMonthCount'])
                ->description("Expiring This Month")
                ->color('warning') // or info
                ->chart([2, 5, 2, 1, 5, 1, 1, 0]),
        ];
    }
}
