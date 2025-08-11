<?php

namespace App\Filament\Admin\Resources\PengajuanKendaraanResource\Pages;

use App\Filament\Admin\Resources\PengajuanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanKendaraans extends ListRecords
{
    protected static string $resource = PengajuanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
