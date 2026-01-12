<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminappPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('adminapp')
            ->path('adminapp')
            ->homeUrl('/admin/dashboard')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Dashboard removed - using custom navigation item below
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Dashboard')
                    ->url('/admin/dashboard')
                    ->icon('heroicon-o-home')
                    ->sort(1)
                    ->isActiveWhen(fn (): bool => request()->is('admin/dashboard')),
                \Filament\Navigation\NavigationItem::make('Agent Comparison')
                    ->url('/admin/reports/agent-policy-comparison')
                    ->icon('heroicon-o-scale')
                    ->sort(2),
                \Filament\Navigation\NavigationItem::make('Account Management')
                    ->url('/admin/account-management')
                    ->icon('heroicon-o-users')
                    ->sort(3),
                \Filament\Navigation\NavigationItem::make('Agent Statement')
                    ->url('/admin/agent-settlements')
                    ->icon('heroicon-o-document-text')
                    ->sort(4),
                \Filament\Navigation\NavigationItem::make('Reports')
                    ->url('/admin/reports')
                    ->icon('heroicon-o-chart-bar')
                    ->sort(5),
                \Filament\Navigation\NavigationItem::make('Agent Listing')
                    ->url('/admin/agent-management')
                    ->icon('heroicon-o-user-group')
                    ->sort(6),
                \Filament\Navigation\NavigationItem::make('Agent Code')
                    ->url('/admin/agent-code-management')
                    ->icon('heroicon-o-hashtag')
                    ->sort(7),
                \Filament\Navigation\NavigationItem::make('Monthly Commissions')
                    ->url('/admin/monthly-commissions')
                    ->icon('heroicon-o-currency-dollar')
                    ->sort(8),
                \Filament\Navigation\NavigationItem::make('Policy Listing')
                    ->url('/admin/policy-list')
                    ->icon('heroicon-o-document-duplicate')
                    ->sort(9),
                \Filament\Navigation\NavigationItem::make('Import Policy')
                    ->url('/admin/upload-policy')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->sort(10),
                \Filament\Navigation\NavigationItem::make('Redeem Request')
                    ->url('/admin/points-redemRequest')
                    ->icon('heroicon-o-gift')
                    ->sort(11),
                \Filament\Navigation\NavigationItem::make('Redeem Proceeded')
                    ->url('/admin/points-redemption')
                    ->icon('heroicon-o-check-circle')
                    ->sort(12),
                \Filament\Navigation\NavigationItem::make('App Slider')
                    ->url('/admin/sliders')
                    ->icon('heroicon-o-photo')
                    ->sort(13),
                \Filament\Navigation\NavigationItem::make('Company')
                    ->url('/admin/companies')
                    ->icon('heroicon-o-building-office')
                    ->sort(14),
                \Filament\Navigation\NavigationItem::make('Insurance Claim')
                    ->url('/admin/claims')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->sort(15),
                \Filament\Navigation\NavigationItem::make('Logs')
                    ->url('/admin/WhatsappMessageLog')
                    ->icon('heroicon-o-document-text')
                    ->sort(17),
                \Filament\Navigation\NavigationItem::make('App Activity')
                    ->url('/admin/app-activity')
                    ->icon('heroicon-o-chart-pie')
                    ->sort(18),
                \Filament\Navigation\NavigationItem::make('Insurance Product')
                    ->url('/admin/insurance-products')
                    ->icon('heroicon-o-briefcase')
                    ->sort(19),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
