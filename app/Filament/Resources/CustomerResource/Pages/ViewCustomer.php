<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Customer Information')
                    ->schema([
                        Components\TextEntry::make('name')
                            ->label('Name'),
                        Components\TextEntry::make('mobile_number')
                            ->label('Mobile Number'),
                        Components\TextEntry::make('email')
                            ->label('Email'),
                        Components\TextEntry::make('username')
                            ->label('Username'),
                    ])
                    ->columns(2),
                
                Components\Section::make('Documents')
                    ->schema([
                        Components\TextEntry::make('aadhar_number')
                            ->label('Aadhar Number'),
                        Components\TextEntry::make('pan_number')
                            ->label('PAN Number'),
                        Components\TextEntry::make('aadhar_document')
                            ->label('Aadhar Document')
                            ->default('Not Uploaded')
                            ->badge()
                            ->color(fn ($state) => $state && $state !== 'Not Uploaded' ? 'success' : 'gray'),
                        Components\TextEntry::make('pan_image')
                            ->label('PAN Image')
                            ->default('Not Uploaded')
                            ->badge()
                            ->color(fn ($state) => $state && $state !== 'Not Uploaded' ? 'success' : 'gray'),
                    ])
                    ->columns(2),
                
                Components\Section::make('Address')
                    ->schema([
                        Components\TextEntry::make('state')
                            ->label('State'),
                        Components\TextEntry::make('city')
                            ->label('City'),
                        Components\TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Components\Section::make('Account Status')
                    ->schema([
                        Components\TextEntry::make('phone_verified_at')
                            ->label('App Active')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Active' : 'Inactive')
                            ->color(fn ($state) => $state ? 'success' : 'gray'),
                        Components\TextEntry::make('customer_policies_count')
                            ->label('Total Policies')
                            ->badge()
                            ->color('primary'),
                        Components\TextEntry::make('created_at')
                            ->label('Registered On')
                            ->dateTime(),
                    ])
                    ->columns(3),
            ]);
    }
}
