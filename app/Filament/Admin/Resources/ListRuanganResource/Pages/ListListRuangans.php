<?php

namespace App\Filament\Admin\Resources\ListRuanganResource\Pages;

use App\Filament\Admin\Resources\ListRuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListListRuangans extends ListRecords
{
    protected static string $resource = ListRuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
