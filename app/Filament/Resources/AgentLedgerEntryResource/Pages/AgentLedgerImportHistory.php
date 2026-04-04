<?php

namespace App\Filament\Resources\AgentLedgerEntryResource\Pages;

use App\Filament\Resources\AgentLedgerEntryResource;
use App\Models\AgentLedgerImportHistory as AgentLedgerImportHistoryModel;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Schema;

class AgentLedgerImportHistory extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = AgentLedgerEntryResource::class;

    protected string $view = 'filament.agent-ledger.import-history-page';

    protected ?string $heading = 'Agent Ledger Import History';

    public function hasImportHistoryTable(): bool
    {
        return Schema::hasTable('agent_ledger_import_histories')
            && Schema::hasTable('agent_ledger_import_history_rows');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AgentLedgerImportHistoryModel::query()->with(['importer', 'rows']))
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Excel File')
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('total_records')
                    ->label('Total Records')
                    ->sortable(),
                Tables\Columns\TextColumn::make('importer.name')
                    ->label('Imported By')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Imported At')
                    ->dateTime('d M Y h:i A')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view_data')
                    ->label('View Data')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (AgentLedgerImportHistoryModel $record): string => "Imported Data - {$record->file_name}")
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (AgentLedgerImportHistoryModel $record) => view('filament.agent-ledger.import-history-rows-modal', [
                        'history' => $record->load(['rows', 'importer']),
                    ])),
            ])
            ->emptyStateHeading('No import history found yet.');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Back to Ledger')
                ->icon('heroicon-o-arrow-left')
                ->url(AgentLedgerEntryResource::getUrl('index')),
        ];
    }
}
