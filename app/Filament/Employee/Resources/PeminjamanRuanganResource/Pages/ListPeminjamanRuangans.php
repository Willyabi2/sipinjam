<?php

namespace App\Filament\Employee\Resources\PeminjamanRuanganResource\Pages;

use App\Filament\Employee\Resources\PeminjamanRuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPeminjamanRuangans extends ListRecords
{
    protected static string $resource = PeminjamanRuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
