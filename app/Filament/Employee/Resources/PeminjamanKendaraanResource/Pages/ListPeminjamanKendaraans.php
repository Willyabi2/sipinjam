<?php

namespace App\Filament\Employee\Resources\PeminjamanKendaraanResource\Pages;

use App\Filament\Employee\Resources\PeminjamanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanKendaraans extends ListRecords
{
    protected static string $resource = PeminjamanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
          Actions\CreateAction::make()
          ->label('New Peminjaman Kendaraan')
          ->icon('heroicon-o-truck')
          ->color('primary'),
          //  Actions\CreateAction::make(),
        ];
    }
}
