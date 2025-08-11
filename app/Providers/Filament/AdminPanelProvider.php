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
use Filament\Navigation\NavigationGroup;
use Filament\Facades\Filament;
use App\Filament\Resources\UserResource; // Import di sini
use Filament\Navigation\NavigationItem; // Tambahkan baris ini
use App\Filament\Resources\PeminjamanKendaraanResource; // Tambahkan ini
use App\Filament\Resources\PeminjamanRuanganResource;   // Dan ini jika diperlukan




class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
           // ->resources([
            //    \App\Filament\Resources\UserResource::class,
          //  ])
           /* ->styles([
                asset('css/filament/app.css'), // <--- tambahkan baris ini
            ])*/

            ->colors([
                'primary' => '#003B71'//Color::Amber,
            ])
            ->font('Poppins')
            ->brandName('SiPinjam Kemenkum')
            ->brandLogoHeight('7rem')
            ->brandLogo(asset('images/admin.png'))
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
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
                MenuItem::make()
                    ->label('Settings')
                    ->url('#')
                    ->icon('heroicon-o-cog-6-tooth'),
                'logout' => MenuItem::make()->label('Log Out'),

            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                    ->label('Manajemen')
                    ->collapsed(false),

            //jika error bawah ini dihapus
            ]);
           /* ->navigationItems([
                NavigationItem::make('Jadwal Kendaraan Disetujui')
                    ->url(fn (): string => PeminjamanKendaraanResource::getUrl('index', ['status' => 'disetujui']))
                    ->icon('heroicon-o-truck')
                    ->group('Manajemen Peminjaman')
                    ->sort(2),
                    
                NavigationItem::make('Jadwal Ruangan Disetujui')
                    ->url(fn (): string => PeminjamanRuanganResource::getUrl('index', ['status' => 'disetujui']))
                    ->icon('heroicon-o-building-office')
                    ->group('Manajemen Peminjaman')
                    ->sort(3)
            ]);*/
            
    }
    public function boot(): void
    {
        Filament::registerRenderHook(
            'panels::head.end',
            fn () => <<<HTML
                <style>
                    .fi-sidebar-header {
                        justify-content: center !important;
                        text-align: center !important;
                    }
                    .fi-sidebar-header .text {
                        width: 100%;
                    }
                </style>
            HTML
        );
    }
   /* public function registerAssets(): void
    {
        \Filament\Facades\Filament::registerStyles([
            asset('css/filament/custom-login.css'), // Ganti jika pakai nama file lain
        ]);
    }*/
}
