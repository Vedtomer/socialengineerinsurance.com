<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentLedgerEntryResource\Pages;
use App\Imports\AgentLedgerImport;
use App\Models\AgentLedgerEntry;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AgentLedgerEntryResource extends Resource
{
    protected static ?string $model = AgentLedgerEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

    protected static ?string $navigationLabel = 'Agent Ledger';

    protected static ?string $modelLabel = 'Agent Ledger Entry';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->leftJoin('users', 'agent_ledger_entries.user_id', '=', 'users.id')
            ->selectRaw('
                MAX(agent_ledger_entries.id) as id,
                agent_ledger_entries.agent_code,
                agent_ledger_entries.user_id,
                users.name as agent_name,
                users.email as agent_email,
                users.mobile_number as agent_mobile_number,
                SUM(agent_ledger_entries.credit) as total_credit,
                SUM(agent_ledger_entries.debit) as total_debit,
                SUM(agent_ledger_entries.credit - agent_ledger_entries.debit) as balance,
                MAX(agent_ledger_entries.entry_date) as latest_entry_date,
                MAX(agent_ledger_entries.created_at) as created_at,
                MAX(agent_ledger_entries.updated_at) as updated_at
            ')
            ->groupBy('agent_ledger_entries.agent_code', 'agent_ledger_entries.user_id', 'users.name', 'users.email', 'users.mobile_number');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('agent_name')
            ->columns([
                Tables\Columns\TextColumn::make('agent_name')
                    ->label('Agent')
                    ->placeholder('-')
                    ->formatStateUsing(fn (AgentLedgerEntry $record): string => trim(($record->agent_name ?: '-') . ' - ' . ($record->agent_code ?: '-')))
                    ->searchable(['users.name', 'agent_ledger_entries.agent_code'])
                    ->color('primary')
                    ->weight('600')
                    ->sortable(query: fn (Builder $query, string $direction) => $query->orderBy('users.name', $direction))
                    ->action(
                        Action::make('view_agent_ledger')
                            ->modalHeading(fn (AgentLedgerEntry $record): string => "{$record->agent_name} Ledger Details")
                            ->modalWidth('7xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Close')
                            ->modalContent(fn (AgentLedgerEntry $record) => view('filament.agent-ledger.agent-details-modal', [
                                'summary' => $record,
                                'entries' => AgentLedgerEntry::query()
                                    ->with('importer')
                                    ->where('agent_code', $record->agent_code)
                                    ->orderBy('entry_date', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->get(),
                            ]))
                    ),
                Tables\Columns\TextColumn::make('total_credit')
                    ->label('Credit')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_debit')
                    ->label('Debit')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('balance')
                    ->label('Balance')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent_mobile_number')
                    ->label('Mobile')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('latest_entry_date')
                    ->label('Last Entry')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->recordUrl(null)
            ->filters([
                Tables\Filters\Filter::make('entry_date')
                    ->form([
                        DatePicker::make('from_date'),
                        DatePicker::make('to_date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from_date'] ?? null, fn ($query, $date) => $query->whereDate('agent_ledger_entries.entry_date', '>=', $date))
                            ->when($data['to_date'] ?? null, fn ($query, $date) => $query->whereDate('agent_ledger_entries.entry_date', '<=', $date));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgentLedgerEntries::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()
            ->distinct('agent_code')
            ->count('agent_code');
    }

    public static function importExcel(array $data): void
    {
        $disk = 'local';
        $path = $data['file'];

        Excel::import(
            new AgentLedgerImport($data['entry_date']),
            Storage::disk($disk)->path($path)
        );

        Notification::make()
            ->success()
            ->title('Excel imported successfully')
            ->body('Rows have been added to Agent Ledger.')
            ->send();
    }
}
