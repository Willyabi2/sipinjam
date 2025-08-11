<?php

namespace App\Filament\Admin\Resources;

use App\Models\PeminjamanRuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\ApprovalPeminjamanResource\Pages;
use App\Filament\Admin\Resources\ApprovalPeminjamanResource\RelationManagers;
use App\Models\ApprovalPeminjaman;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Textarea;

class ApprovalPeminjamanResource extends Resource
{
    protected static ?string $model = PeminjamanRuangan::class;
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $modelLabel = 'Persetujuan Peminjaman Ruangan';
    protected static ?string $navigationLabel = 'Persetujuan Peminjaman Ruangan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form schema jika diperlukan
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pegawai.name')->label('Pegawai'),
                TextColumn::make('ruangan.nama_ruangan'),
                TextColumn::make('ruangan.tata_letak')
                        ->label('Tata Letak'),
                        //->wrap() //Untuk text panjang
                        //->description(fn ($record) => $record->ruangan?->nama_ruangan),
                TextColumn::make('tanggal_mulai'),
                TextColumn::make('kegiatan'),
                TextColumn::make('tanggal_selesai'),
                TextColumn::make('waktu_mulai'),
                TextColumn::make('waktu_selesai'),
                TextColumn::make('penanggung_jawab'),
                TextColumn::make('perlengkapan_tambahan')
                        ->label('Perlengkapan Tambahan')
                        ->wrap(),
                TextColumn::make('keterangan'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                // Filters jika diperlukan
            ])
            ->actions([
                Action::make('approve')
                    ->button()
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (PeminjamanRuangan $record) {
                        $record->update(['status' => 'approved']);
                        Notification::make()
                            ->title('Approved successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PeminjamanRuangan $record): bool => $record->status === 'pending'),

                Action::make('reject')
                    ->button()
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->form([
                        Forms\Components\Textarea::make('alasan_penolakan')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function (PeminjamanRuangan $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'alasan_penolakan' => $data['alasan_penolakan']
                        ]);
                        Notification::make()
                            ->title('Rejected successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PeminjamanRuangan $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relations jika diperlukan
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApprovalPeminjamen::route('/'),
            // Hapus create dan edit jika tidak diperlukan
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->with(['ruangan', 'pegawai']) // ⭐ Inilah tempat eager loading ditambahkan
        ->where('status', 'pending');
    }
}