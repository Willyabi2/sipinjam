<?php

namespace App\Filament\Employee\Resources\PeminjamanRuanganResource\Pages;

use App\Filament\Employee\Resources\PeminjamanRuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;  // Tambahkan ini di bagian use statements
use App\Models\PeminjamanRuangan;
use Exception;


class CreatePeminjamanRuangan extends CreateRecord
{
    protected static string $resource = PeminjamanRuanganResource::class;
    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Ajukan') // Ubah label tombol di sini
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Buat Pengajuan Lagi') // Ubah label tombol "create another"
            //->label('Ajukan & buat lagi') // Ubah label tombol "create another"
            ->action('createAnother')
            ->color('gray')
            ->keyBindings(['mod+shift+s']);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
{
    $exists = PeminjamanRuangan::where('ruangan_id', $data['ruangan_id'])
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($data) {
                $query->whereBetween('tanggal_mulai', [$data['tanggal_mulai'], $data['tanggal_selesai']])
                    ->orWhereBetween('tanggal_selesai', [$data['tanggal_mulai'], $data['tanggal_selesai']]);
            })
            ->where(function ($query) use ($data) {
                $query->whereBetween('waktu_mulai', [$data['waktu_mulai'], $data['waktu_selesai']])
                    ->orWhereBetween('waktu_selesai', [$data['waktu_mulai'], $data['waktu_selesai']]);
            })
    ->exists();

    if ($exists) {
        throw new \Exception('Ruangan tidak tersedia pada tanggal dan jam tersebut');
    }

    return $data;
}
}
