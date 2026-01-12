<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Customers';
    
    protected static ?string $modelLabel = 'Customer';

    protected static ?int $navigationSort = 16;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('customer')->withCount('customerPolicies');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, $context) {
                        if ($context === 'create') {
                            // Generate username from name (slug-like, unique)
                            $username = \Illuminate\Support\Str::slug($state);
                            $originalUsername = $username;
                            $counter = 1;
                            
                            // Ensure uniqueness
                            while (\App\Models\User::where('username', $username)->exists()) {
                                $username = $originalUsername . $counter;
                                $counter++;
                            }
                            
                            $set('username', $username);
                        }
                    }),
                Forms\Components\TextInput::make('mobile_number')
                    ->label('Mobile Number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pincode')
                    ->label('Pincode')
                    ->maxLength(6)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (strlen($state) === 6) {
                            // You can integrate with an API here to fetch state/city
                            // For now, using a simple example
                            // In production, use India Post API or similar service
                            $pincodeData = self::getPincodeData($state);
                            if ($pincodeData) {
                                $set('state', $pincodeData['state']);
                                $set('city', $pincodeData['city']);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('state')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('username')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('aadhar_number')
                    ->label('Aadhar Number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pan_number')
                    ->label('PAN Number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    protected static function getPincodeData($pincode)
    {
        // Simple pincode to state/city mapping
        // In production, integrate with India Post API or similar service
        $pincodeMap = [
            '110001' => ['state' => 'DELHI', 'city' => 'New Delhi'],
            '132103' => ['state' => 'HARYANA', 'city' => 'Panipat'],
            '160001' => ['state' => 'CHANDIGARH', 'city' => 'Chandigarh'],
            // Add more mappings or use API
        ];

        return $pincodeMap[$pincode] ?? null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('customer_policies_count')
                    ->label('Policies')
                    ->badge()
                    ->color('primary')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('mobile_number')
                    ->label('Mobile')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\IconColumn::make('phone_verified_at')
                    ->label('App Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('app_active')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('phone_verified_at'))
                    ->label('App Active'),
                Tables\Filters\Filter::make('inactive')
                    ->query(fn (Builder $query): Builder => $query->whereNull('phone_verified_at'))
                    ->label('Inactive'),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->modalHeading('Customer Details')
                    ->modalWidth('3xl'),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\Action::make('change_password')
                    ->label('')
                    ->tooltip('Change Password')
                    ->icon('heroicon-o-lock-closed')
                    ->iconButton()
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->minLength(6),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->same('new_password'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'password' => bcrypt($data['new_password']),
                        ]);
                    })
                    ->successNotificationTitle('Password changed successfully'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->before(function (User $record, Tables\Actions\DeleteAction $action) {
                        if ($record->customerPolicies()->count() > 0) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Cannot Delete Customer')
                                ->body('This customer has ' . $record->customerPolicies()->count() . ' policies. Please delete all policies first.')
                                ->persistent()
                                ->send();
                            
                            $action->cancel();
                        }
                    }),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
