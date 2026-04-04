<?php

namespace App\Filament\Resources\AgentLedgerEntryResource\Pages;

use App\Filament\Resources\AgentLedgerEntryResource;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;

class ListAgentLedgerEntries extends ListRecords
{
    protected static string $resource = AgentLedgerEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('import_history')
                ->label('Import History')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->visible(fn (): bool => Schema::hasTable('agent_ledger_import_histories'))
                ->url(fn (): string => AgentLedgerEntryResource::getUrl('history')),
            Actions\Action::make('import_excel')
                ->label('Import Agent Ledger')
                ->modalHeading('Import Agent Ledger')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalWidth('xl')
                ->form([
                    Grid::make(2)
                        ->schema([
                            Placeholder::make('entry_date_label')
                                ->label('')
                                ->content(new HtmlString('<div class="font-medium text-sm text-gray-700">Date <span class="text-danger-600">*</span></div>')),
                            DatePicker::make('entry_date')
                                ->label('')
                                ->required(),
                            Placeholder::make('file_label')
                                ->label('')
                                ->content(new HtmlString('<div class="font-medium text-sm text-gray-700">Excel File <span class="text-danger-600">*</span></div>')),
                            FileUpload::make('file')
                                ->label('')
                                ->disk('local')
                                ->directory('imports/agent-ledger')
                                ->storeFileNamesIn('original_file_name')
                                ->acceptedFileTypes([
                                    'application/vnd.ms-excel',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'text/csv',
                                ])
                                ->helperText(new HtmlString(
                                    '<a href="' . asset('sample/agent-ledger-sample.csv') . '" download class="text-primary-600 text-xs underline">Download sample CSV</a>'
                                ))
                                ->required(),
                        ]),
                ])
                ->action(fn (array $data) => AgentLedgerEntryResource::importExcel($data)),
        ];
    }
}
