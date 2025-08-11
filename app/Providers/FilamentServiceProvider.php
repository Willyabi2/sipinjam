<?php

namespace App\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Filament\FilamentServiceProvider as BaseFilamentServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

   /*public function panel(Panel $panel): Panel
    {
        //
       /* {
            return $panel
                ->styles([
                    asset('css/filament/custom-login.css'), // atau path CSS kamu
                ]);
        }
    }*/

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
