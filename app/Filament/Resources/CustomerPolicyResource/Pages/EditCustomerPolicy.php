<?php

namespace App\Filament\Resources\CustomerPolicyResource\Pages;

use App\Filament\Resources\CustomerPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomerPolicy extends EditRecord
{
    protected static string $resource = CustomerPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
