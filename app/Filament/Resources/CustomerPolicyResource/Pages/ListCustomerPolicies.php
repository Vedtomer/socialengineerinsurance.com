<?php

namespace App\Filament\Resources\CustomerPolicyResource\Pages;

use App\Filament\Resources\CustomerPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomerPolicies extends ListRecords
{
    protected static string $resource = CustomerPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    // Get the filtered query from the table
                    $query = $this->getFilteredTableQuery();
                    
                    return response()->streamDownload(function () use ($query) {
                        $columns = ['Policy No', 'Customer', 'Start Date', 'End Date', 'Status', 'Net Amount', 'GST', 'Premium', 'Insurance Company', 'Policy Type'];
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $columns);
                        
                        $query->with('customer')
                            ->chunk(100, function ($policies) use ($file) {
                                foreach ($policies as $policy) {
                                    fputcsv($file, [
                                        $policy->policy_no,
                                        $policy->customer->name ?? 'N/A',
                                        $policy->policy_start_date,
                                        $policy->policy_end_date,
                                        $policy->status,
                                        $policy->net_amount,
                                        $policy->gst,
                                        $policy->premium,
                                        $policy->insurance_company,
                                        $policy->policy_type,
                                    ]);
                                }
                            });
                            
                        fclose($file);
                    }, 'customer-policies-' . date('Y-m-d-His') . '.csv');
                }),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerPolicyResource\Widgets\CustomerPolicyStats::class,
        ];
    }
}
