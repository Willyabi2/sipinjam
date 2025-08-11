<?php

namespace App\Filament\Admin\Resources\ListRuanganResource\Pages;

use App\Filament\Admin\Resources\ListRuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditListRuangan extends EditRecord
{
    protected static string $resource = ListRuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
           // Actions\DeleteAction::make(),
        ];
    }
}
