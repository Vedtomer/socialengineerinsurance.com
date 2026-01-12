<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerPolicyResource\Pages;
use App\Filament\Resources\CustomerPolicyResource\RelationManagers;
use App\Models\CustomerPolicy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerPolicyResource extends Resource
{
    protected static ?string $model = CustomerPolicy::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Customer Policies';
    protected static ?int $navigationSort = 16;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('policy_no')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('policy_start_date')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    // Auto-fill end date as 1 year from start date
                                    $endDate = \Carbon\Carbon::parse($state)->addYear();
                                    $set('policy_end_date', $endDate->format('Y-m-d'));
                                }
                            }),
                        Forms\Components\DatePicker::make('policy_end_date')
                            ->required(),
                        Forms\Components\Select::make('user_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('policy_holder_name')
                            ->maxLength(299),
                        Forms\Components\Hidden::make('status')
                            ->default('active'),
                        Forms\Components\TextInput::make('net_amount')
                            ->label('Net Amount')
                            ->required()
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $gst = $get('gst') ?? 2.5;
                                $netAmount = floatval($state);
                                $premium = $netAmount + ($netAmount * $gst / 100);
                                $set('premium', number_format($premium, 2, '.', ''));
                            }),
                        Forms\Components\TextInput::make('gst')
                            ->label('GST (%)')
                            ->required()
                            ->numeric()
                            ->default(2.5)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $netAmount = floatval($get('net_amount') ?? 0);
                                $gst = floatval($state);
                                $premium = $netAmount + ($netAmount * $gst / 100);
                                $set('premium', number_format($premium, 2, '.', ''));
                            }),
                        Forms\Components\TextInput::make('premium')
                            ->label('Premium')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('insurance_company')
                            ->label('Insurance Company')
                            ->options([
                                'HDFC ERGO' => 'HDFC ERGO',
                                'ICICI Lombard' => 'ICICI Lombard',
                                'Bajaj Allianz' => 'Bajaj Allianz',
                                'Reliance General' => 'Reliance General',
                                'Tata AIG' => 'Tata AIG',
                                'New India Assurance' => 'New India Assurance',
                                'Oriental Insurance' => 'Oriental Insurance',
                                'United India Insurance' => 'United India Insurance',
                                'National Insurance' => 'National Insurance',
                                'Other' => 'Other',
                            ])
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('policy_type')
                            ->label('Policy Type')
                            ->options([
                                'life_insurance' => 'Life Insurance',
                                'health_insurance' => 'Health Insurance',
                                'general_insurance' => 'General Insurance',
                            ])
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('customer.name') // Assuming 'customer' relationship exists
                    ->label('Customer')
                    ->searchable()
                    ->limit(16)
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('policy_no')
                    ->label('Policy No.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('policy_start_date')
                    ->label('Start Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('policy_end_date')
                    ->label('Due Date')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired' => 'danger',
                        'cancelled' => 'warning',
                        'pending' => 'gray',
                        'approved' => 'primary',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('date_filter')
                    ->form([
                        Forms\Components\Radio::make('date_type')
                            ->label('Filter By')
                            ->options([
                                'policy_start_date' => 'Policy Start Date',
                                'policy_end_date' => 'Policy Due Date',
                            ])
                            ->default('policy_start_date')
                            ->inline(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('year')
                                    ->label('Year')
                                    ->options(array_combine(
                                        range(2024, date('Y') + 2),
                                        range(2024, date('Y') + 2)
                                    )),
                                Forms\Components\Select::make('month')
                                    ->label('Month')
                                    ->options(array_reduce(range(1, 12), function ($carry, $m) {
                                        $carry[$m] = date('F', mktime(0, 0, 0, $m, 1));
                                        return $carry;
                                    }, [])),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $column = $data['date_type'] ?? 'policy_start_date';
                        return $query
                            ->when($data['year'], fn ($query, $year) => $query->whereYear($column, $year))
                            ->when($data['month'], fn ($query, $month) => $query->whereMonth($column, $month));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        $typeLabel = ($data['date_type'] ?? 'policy_start_date') === 'policy_end_date' ? 'Due Date' : 'Start Date';
                        
                        if ($data['year'] ?? null) {
                            $indicators[] = "$typeLabel: Year {$data['year']}";
                        }
                        if ($data['month'] ?? null) {
                            $monthName = date('F', mktime(0, 0, 0, $data['month'], 1));
                            $indicators[] = "Month: $monthName";
                        }
                        return $indicators;
                    }),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerPolicies::route('/'),
            'create' => Pages\CreateCustomerPolicy::route('/create'),
            'edit' => Pages\EditCustomerPolicy::route('/{record}/edit'),
        ];
    }
}
