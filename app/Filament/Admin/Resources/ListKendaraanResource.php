<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ListKendaraanResource\Pages;
use App\Filament\Admin\Resources\ListKendaraanResource\RelationManagers;
use App\Models\ListKendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PeminjamanKendaraan;
use App\Models\Kendaraan;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Closure;
use Filament\Tables\Actions\EditAction;

class ListKendaraanResource extends Resource
{
    protected static ?string $model = ListKendaraan::class;
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $modelLabel = 'List Kendaraan';
    protected static ?string $navigationLabel = 'List Kendaraan';

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
            TextColumn::make('kendaraan.jenis_kendaraan')
            ->label('jenis kendaraan')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('kendaraan.warna_kendaraan')
            ->label('Warna '),

            TextColumn::make('kendaraan.merk_kendaraan')
                    ->label('Merk Kendaraan'),
                
            TextColumn::make('kendaraan.plat_nomor')
            ->label('Plat Nomor')
                ->searchable(),
                
            TextColumn::make('pegawai.name')
                ->label('Pegawai')
                ->sortable()
                ->searchable(),
                
            TextColumn::make('kegiatan')
                ->searchable()
                ->wrap(),
                
            TextColumn::make('tanggal_mulai')
                ->date()
                ->sortable(),
                
            TextColumn::make('tanggal_selesai')
                ->date()
                ->sortable(),
                
            TextColumn::make('waktu_mulai')
                ->time(),
                
            TextColumn::make('waktu_selesai')
                ->time(),
                
            TextColumn::make('penanggung_jawab')
                ->searchable(),
                
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                })
                ->sortable(),
                
            TextColumn::make('alasan_penolakan')
                //->visible(fn ($record) => optional($record)->status === 'rejected')
                ->wrap(),
        ])
            ->filters([
                //
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
            'index' => Pages\ListListKendaraans::route('/'),
           // 'create' => Pages\CreateListKendaraan::route('/create'),
          //  'edit' => Pages\EditListKendaraan::route('/{record}/edit'),
        ];
    }
}
