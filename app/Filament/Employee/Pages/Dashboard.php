<?php

namespace App\Filament\Employee\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.employee.pages.dashboard';

    protected static ?string $title = 'Dashboard Pegawai';

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Sembunyikan dari navigasi karena akan jadi homepage
    }
}
