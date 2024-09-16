<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Outerweb\FilamentTranslatableFields\Filament\Plugins\FilamentTranslatableFieldsPlugin;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Filament\Notifications\Livewire\DatabaseNotifications;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationItems([
                NavigationItem::make('telescope')
                    ->label(fn(): string => __('navigation.telescope'))
                    ->badge(fn(): string => '●')
                    ->badgeTooltip(fn(): string => __('navigation.telescope-tooltip'))
                    ->url(fn(): string => route('telescope'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-chart-bar-square')
                    ->group(fn(): string => __('navigation.monitor'))
                    ->visible(fn(): bool => !app()->environment('testing') && Auth::user()->isAdmin),

                NavigationItem::make('pulse')
                    ->label(fn(): string => __('navigation.pulse'))
                    ->badge(fn(): string => '●')
                    ->badgeTooltip(fn(): string => __('navigation.pulse-tooltip'))
                    ->url(fn(): string => route('pulse'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-heart')
                    ->group(fn(): string => __('navigation.monitor'))
                    ->visible(fn(): bool => !app()->environment('testing') && Auth::user()->isAdmin),

                NavigationItem::make('horizon')
                    ->label(fn(): string => __('navigation.horizon'))
                    ->badge(fn(): string => '●')
                    ->badgeTooltip(fn(): string => __('navigation.horizon-tooltip'))
                    ->url(fn(): string => route('horizon.index'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-lifebuoy')
                    ->group(fn(): string => __('navigation.monitor'))
                    ->visible(fn(): bool => !app()->environment('testing') && Auth::user()->isAdmin),
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('1s')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins([
                FilamentTranslatableFieldsPlugin::make()
                    ->supportedLocales(config('app.supported_locales')),
                ActivitylogPlugin::make(),
                FilamentShieldPlugin::make(),
            ]);
    }
}
