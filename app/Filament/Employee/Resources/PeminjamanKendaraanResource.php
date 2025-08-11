<?php

namespace App\Filament\Employee\Resources;

use App\Models\PeminjamanKendaraan;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Filament\Forms\Get;
use App\Filament\Employee\Resources\PeminjamanKendaraanResource\Pages;
use App\Filament\Employee\Resources\PeminjamanKendaraanResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Closure;
use Filament\Tables\Actions\EditAction;

class PeminjamanKendaraanResource extends Resource
{
    protected static ?string $model = PeminjamanKendaraan::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $modelLabel = 'Peminjaman Kendaraan';
    protected static ?string $navigationLabel = 'Peminjaman Kendaraan';

    protected static ?string $navigationGroup = 'Peminjaman';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kendaraan_id')
                    ->label('Kendaraan')
                    ->options(
                        Kendaraan::query()
                            ->selectRaw("id, CONCAT(jenis_kendaraan, ' - ', plat_nomor) as full_label")
                            ->pluck('full_label', 'id')
                    )
                    ->required()
                    ->live()
                    ->searchable()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $kendaraan = Kendaraan::find($state);
                        if ($kendaraan) {
                            $set('merk_kendaraan', $kendaraan->merk_kendaraan);
                            $set('warna_kendaraan', $kendaraan->warna_kendaraan);
                        }
                    }),
                    
                TextInput::make('warna_kendaraan')
                    ->label('Warna Kendaraan')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('merk_kendaraan')
                    ->label('Merk Kendaraan')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('pegawai_name')
                    ->label('Pegawai')
                    ->disabled()
                    ->dehydrated()
                    ->default(fn() => auth('employee')->user()->name),
                    
                TextInput::make('kegiatan')
                    ->required()
                    ->maxLength(255),
                    
                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->rules([
                        'required',
                        'date',
                        'after_or_equal:today',
                        function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $kendaraanId = $get('kendaraan_id');
                                $tanggalSelesai = $get('tanggal_selesai');
                                $waktuMulai = $get('waktu_mulai');
                                $waktuSelesai = $get('waktu_selesai');

                                if (!$kendaraanId || !$tanggalSelesai || !$waktuMulai || !$waktuSelesai) {
                                    return;
                                }

                                $start = Carbon::parse($value . ' ' . $waktuMulai);
                                $end = Carbon::parse($tanggalSelesai . ' ' . $waktuSelesai);

                                if (!$start->lt($end)) {
                                    $fail('Waktu mulai harus sebelum waktu selesai');
                                    return;
                                }

                                if (!PeminjamanKendaraan::isAvailable($kendaraanId, $start, $end)) {
                                    $fail('Kendaraan tidak tersedia pada tanggal dan waktu yang diminta');
                                }
                            };
                        }
                    ]),
                    
                DatePicker::make('tanggal_selesai')
                    ->required()
                    ->rules([
                        'required',
                        'date',
                        'after_or_equal:tanggal_mulai'
                    ]),
                    
                TimePicker::make('waktu_mulai')
                    ->required()
                    ->seconds(false),
                    
                TimePicker::make('waktu_selesai')
                    ->required()
                    ->seconds(false)
                    ->rules([
                        function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $waktuMulai = $get('waktu_mulai');
                                if ($value && $waktuMulai && $value <= $waktuMulai) {
                                    $fail('Waktu selesai harus setelah waktu mulai');
                                }
                            };
                        }
                    ]),
                    
                Textarea::make('perlengkapan_tambahan')
                    ->label('Perlengkapan Tambahan')
                    ->columnSpanFull(),
                    
                TextInput::make('penanggung_jawab')
                    ->required()
                    ->maxLength(255),
                    
                Textarea::make('keterangan')
                    ->columnSpanFull(),

                Textarea::make('alasan_penolakan')
                    ->label('Alasan penolakan')
                    ->visible(fn ($record): bool => optional($record)->status === 'rejected')
                    ->disabled()
                    ->columnSpanFull(),
                
                Hidden::make('pegawai_id')
                    ->default(fn () => auth('employee')->id())
                    ->dehydrated()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kendaraan.jenis_kendaraan')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('kendaraan.plat_nomor')
                ->label('Plat Nomor')
                    ->searchable(),

                TextColumn::make('kendaraan.warna_kendaraan')
                ->label('Warna'),

                TextColumn::make('kendaraan.merk_kendaraan')
                    ->label('Merk Kendaraan'),
                    
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PeminjamanKendaraan $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([])
            ->defaultSort('tanggal_mulai', 'desc')
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamanKendaraans::route('/'),
            'create' => Pages\CreatePeminjamanKendaraan::route('/create'),
            //'edit' => Pages\EditPeminjamanKendaraan::route('/{record}/edit'),
        ];
    }
}