<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ListRuanganResource\Pages;
use App\Filament\Admin\Resources\ListRuanganResource\RelationManagers;
use App\Models\ListRuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter; // Tambahkan baris ini
use Filament\Tables\Columns\TextColumn;



class ListRuanganResource extends Resource
{
    protected static ?string $model = ListRuangan::class;
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $modelLabel = 'List Ruangan';
    protected static ?string $navigationLabel = 'List  Ruangan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ruangan.nama')
                    ->label('Ruangan'),
                Tables\Columns\TextColumn::make('kegiatan')
                    ->label('Kegiatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pegawai.name')
                    ->label('Pegawai'),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date(),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->time(),
                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->label('Penanggung Jawab'),
                Tables\Columns\TextColumn::make('perlengkapan_tambahan')
                    ->label('Perlengkapan Tambahan'),
                    TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                    TextColumn::make('alasan_penolakan')
                    //->visible(fn ($record) => optional($record)->status === 'rejected')
                    ->wrap()//text panjang
                    //TextColumn::make('alasan_penolakan')
                    //->visible(fn (PeminjamanRuangan $record): bool => $record->status === 'rejected'),
                ])
            ->filters([
                //
                Filter::make('disetujui')
                ->query(fn ($query) => $query->where('status', 'disetujui'))
                ->label('Yang Sudah Disetujui')
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListRuangans::route('/'),
           // 'create' => Pages\CreateListRuangan::route('/create'),
            //'edit' => Pages\EditListRuangan::route('/{record}/edit'),
        ];
    }
}
