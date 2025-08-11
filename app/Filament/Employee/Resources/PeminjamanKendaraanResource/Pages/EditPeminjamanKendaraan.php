<?php

namespace App\Filament\Employee\Resources\PeminjamanKendaraanResource\Pages;

use App\Filament\Employee\Resources\PeminjamanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPeminjamanKendaraan extends EditRecord
{
    protected static string $resource = PeminjamanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
