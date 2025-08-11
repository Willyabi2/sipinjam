<?php

namespace App\Filament\Admin\Resources\ListKendaraanResource\Pages;

use App\Filament\Admin\Resources\ListKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListKendaraans extends ListRecords
{
    protected static string $resource = ListKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\CreateAction::make(),
        ];
    }
}
