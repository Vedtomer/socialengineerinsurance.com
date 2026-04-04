<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentLedgerEntryResource\Pages;
use App\Imports\AgentLedgerImport;
use App\Models\AgentLedgerEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AgentLedgerEntryResource extends Resource
{
    protected static ?string $model = AgentLedgerEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-on-square';

    protected static ?string $navigationLabel = 'Agent Ledger';

    protected static ?string $modelLabel = 'Agent Ledger Entry';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('entry_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent_code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.name')
                    ->label('Agent')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('credit')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('debit')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('importer.name')
                    ->label('Imported By')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Imported At')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('entry_date')
                    ->form([
                        Forms\Components\DatePicker::make('from_date'),
                        Forms\Components\DatePicker::make('to_date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '>=', $date))
                            ->when($data['to_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        return (string) static::getModel()::count();
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
