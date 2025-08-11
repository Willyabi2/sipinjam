<?php

namespace App\Filament\Employee\Resources\PeminjamanKendaraanResource\Pages;

use App\Filament\Employee\Resources\PeminjamanKendaraanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use App\Models\PeminjamanKendaraan;
use Exception;

class CreatePeminjamanKendaraan extends CreateRecord
{
    protected static string $resource = PeminjamanKendaraanResource::class;

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Ajukan Peminjaman') // Ubah label tombol
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Ajukan & Buat Lagi') // Ubah label tombol
            ->action('createAnother')
            ->color('gray')
            ->keyBindings(['mod+shift+s']);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Validasi ketersediaan kendaraan
        $exists = PeminjamanKendaraan::where('kendaraan_id', $data['kendaraan_id'])
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
            throw new Exception('Kendaraan tidak tersedia pada tanggal dan jam tersebut');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Ajukan Peminjaman Baru')
                ->icon('heroicon-o-truck')
                ->color('primary'),
        ];
    }
}