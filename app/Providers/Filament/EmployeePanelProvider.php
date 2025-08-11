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
use Filament\Navigation\MenuItem;
use Filament\Facades\Filament;
use App\Http\Middleware\AuthenticateEmployee;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->authGuard('employee')
            ->login(\App\Filament\Employee\Pages\Auth\Login::class)
            ->colors([
                'primary' => '#003B71'//Color::Amber,
            ])
            ->font('Poppins')
            ->brandName('SiPinjam Kemenkum')
            ->brandLogoHeight('7rem')
            ->brandLogo(asset('images/pegawai.png'))
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\\Filament\\Employee\\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\\Filament\\Employee\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\\Filament\\Employee\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->spa() //spa mode
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
            ->userMenuItems([
                'logout' => MenuItem::make()->label('Log Out'),
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Peminjaman')
                    ->collapsed(false),
            ]);
    }

    public function boot(): void
    {
        Filament::registerRenderHook(
            'panels::head.end',
            fn () => <<<HTML
                <style>
                    /* Center brand name text in sidebar */
                    .fi-sidebar-header {
                        justify-content: center !important;
                        text-align: center !important;
                        flex-direction: column !important;
                        align-items: center !important;
                    }

                    .fi-sidebar-header .text {
                        text-align: center !important;
                        font-size: 0.875rem; /* Optional: adjust size */
                        line-height: 1.25rem;
                        font-weight: 600;
                        white-space: normal; /* Allow wrapping */
                        max-width: 160px; /* Limit width if text too long */
                    }
                </style>
            HTML
        );
    }
}
