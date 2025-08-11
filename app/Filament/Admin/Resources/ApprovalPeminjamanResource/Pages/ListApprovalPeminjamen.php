<?php

namespace App\Filament\Admin\Resources\ApprovalPeminjamanResource\Pages;

use App\Filament\Admin\Resources\ApprovalPeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalPeminjamen extends ListRecords
{
    protected static string $resource = ApprovalPeminjamanResource::class;

    protected function getHeaderActions(): array
    {
        return [
//Actions\CreateAction::make(),
      ];
    }
}
