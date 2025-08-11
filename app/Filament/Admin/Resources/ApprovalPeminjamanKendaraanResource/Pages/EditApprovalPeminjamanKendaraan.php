<?php

namespace App\Filament\Admin\Resources\ApprovalPeminjamanKendaraanResource\Pages;

use App\Filament\Admin\Resources\ApprovalPeminjamanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalPeminjamanKendaraan extends EditRecord
{
    protected static string $resource = ApprovalPeminjamanKendaraanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
