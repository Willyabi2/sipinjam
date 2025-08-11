<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KendaraanResource\Pages;
use App\Filament\Admin\Resources\KendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\ManageRecords;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Peminjaman';
    protected static ?string $navigationLabel = 'Kendaraan';

    //protected static ?int $navigationSort = 5; // Sesuaikan urutan menu

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('jenis_kendaraan')
                ->label('Jenis Kendaraan')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('merk_kendaraan')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('warna_kendaraan')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('plat_nomor')
                ->unique()
                ->required()
                ->maxLength(20),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('jenis_kendaraan')
                ->searchable(),

            Tables\Columns\TextColumn::make('merk_kendaraan')
                ->searchable(),

            Tables\Columns\TextColumn::make('warna_kendaraan'),

            Tables\Columns\TextColumn::make('plat_nomor')
                ->searchable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }
}
