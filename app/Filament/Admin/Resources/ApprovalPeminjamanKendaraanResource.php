<?php

namespace App\Filament\Admin\Resources;

use App\Models\PeminjamanKendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\ApprovalPeminjamanKendaraanResource\Pages;

class ApprovalPeminjamanKendaraanResource extends Resource
{
    protected static ?string $model = PeminjamanKendaraan::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $modelLabel = 'Persetujuan Peminjaman Kendaraan';
    protected static ?string $navigationLabel = 'Persetujuan Peminjaman Kendaraan';
    protected static ?string $navigationGroup = 'Manajemen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form schema untuk create/edit jika diperlukan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.name')
                    ->label('Pegawai')
                    ->searchable(),
                    
                TextColumn::make('kendaraan.plat_nomor')
                    ->label('Plat Nomor')
                    ->searchable()
                    ->description(fn ($record) => 
                        $record->kendaraan->merk_kendaraan . ' • ' . 
                        $record->kendaraan->jenis_kendaraan . ' • ' .
                        $record->kendaraan->warna_kendaraan),
                    
                TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->wrap(),
                    
                TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date()
                    ->description(fn ($record) => $record->waktu_mulai),
                    
                TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date()
                    ->description(fn ($record) => $record->waktu_selesai),
                    
                TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    }),
                    
                // Kolom tersembunyi
                TextColumn::make('perlengkapan_tambahan')
                    ->label('Perlengkapan')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('keterangan')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('alasan_penolakan')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
                Tables\Filters\SelectFilter::make('kendaraan.jenis_kendaraan')
                    ->label('Jenis Kendaraan')
                    ->options(fn () => \App\Models\Kendaraan::pluck('jenis_kendaraan', 'jenis_kendaraan')->unique()),
            ])
            ->actions([
                Action::make('approve')
                    ->button()
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui peminjaman kendaraan ini?')
                    ->action(function (PeminjamanKendaraan $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Peminjaman Disetujui')
                            ->body('Peminjaman kendaraan ' . $record->kendaraan->plat_nomor . ' telah disetujui.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PeminjamanKendaraan $record): bool => $record->status === 'pending'),

                Action::make('reject')
                    ->button()
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Peminjaman')
                    ->modalDescription('Apakah Anda yakin ingin menolak peminjaman kendaraan ini?')
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (PeminjamanKendaraan $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'alasan_penolakan' => $data['alasan_penolakan']
                        ]);
                        Notification::make()
                            ->title('Peminjaman Ditolak')
                            ->body('Peminjaman kendaraan ' . $record->kendaraan->plat_nomor . ' ditolak. Alasan: ' . $data['alasan_penolakan'])
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (PeminjamanKendaraan $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_mulai', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan relations jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovalPeminjamanKendaraans::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['kendaraan', 'pegawai'])
            ->where('status', 'pending');
    }
}