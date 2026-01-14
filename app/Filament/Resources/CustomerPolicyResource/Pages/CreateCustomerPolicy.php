<?php

namespace App\Filament\Resources\CustomerPolicyResource\Pages;

use App\Filament\Resources\CustomerPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomerPolicy extends CreateRecord
{
    protected static string $resource = CustomerPolicyResource::class;

    protected function afterCreate(): void
    {
        // Rename uploaded policy document to policy_no.pdf
        if ($this->record->policy_document) {
            $oldPath = $this->record->policy_document;
            $newFileName = $this->record->policy_no . '.pdf';
            $newPath = 'customer_policies/' . $newFileName;
            
            \Storage::disk('public')->move($oldPath, $newPath);
            
            // Update the record without triggering events
            $this->record->update(['policy_document' => null]);
        }
    }
}
