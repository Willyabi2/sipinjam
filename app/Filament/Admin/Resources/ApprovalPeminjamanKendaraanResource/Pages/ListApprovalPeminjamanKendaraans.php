<?php

namespace App\Filament\Admin\Resources\ApprovalPeminjamanKendaraanResource\Pages;

use App\Filament\Admin\Resources\ApprovalPeminjamanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalPeminjamanKendaraans extends ListRecords
{
    protected static string $resource = ApprovalPeminjamanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
          //  Actions\CreateAction::make(),
        ];
    }
}
