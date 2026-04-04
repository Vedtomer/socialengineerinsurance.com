<?php

namespace App\Filament\Resources\AgentLedgerEntryResource\Pages;

use App\Filament\Resources\AgentLedgerEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAgentLedgerEntries extends ListRecords
{
    protected static string $resource = AgentLedgerEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('entry_date')
                        ->label('Date')
                        ->required(),
                    \Filament\Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        ->directory('imports/agent-ledger')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(fn (array $data) => AgentLedgerEntryResource::importExcel($data)),
        ];
    }
}
