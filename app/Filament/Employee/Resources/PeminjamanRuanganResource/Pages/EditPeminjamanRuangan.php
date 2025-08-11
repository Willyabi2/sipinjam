<?php

namespace App\Filament\Employee\Resources\PeminjamanRuanganResource\Pages;

use App\Filament\Employee\Resources\PeminjamanRuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use App\Models\PeminjamanRuangan;
use Exception;

class EditPeminjamanRuangan extends EditRecord
{
    protected static string $resource = PeminjamanRuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
{
    $exists = PeminjamanRuangan::where('id', '!=', $this->record->id)
        ->booked(
            $data['ruangan_id'],
            $data['tanggal_mulai'],
            $data['tanggal_selesai'],
            $data['waktu_mulai'],
            $data['waktu_selesai']
        )->exists();

    if ($exists) {
        throw new \Exception('Ruangan tidak tersedia pada tanggal dan jam tersebut');
    }

    return $data;
}
}
