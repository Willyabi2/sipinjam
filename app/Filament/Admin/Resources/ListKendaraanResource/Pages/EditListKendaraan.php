<?php

namespace App\Filament\Admin\Resources\ListKendaraanResource\Pages;

use App\Filament\Admin\Resources\ListKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListKendaraan extends EditRecord
{
    protected static string $resource = ListKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
