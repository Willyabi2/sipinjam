<?php

namespace App\Filament\Employee\Resources;

use App\Models\PeminjamanRuangan;
use App\Models\Ruangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Filament\Forms\Get;
use App\Filament\Employee\Resources\PeminjamanRuanganResource\Pages;
use App\Filament\Employee\Resources\PeminjamanRuanganResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;
use Closure;
use Filament\Tables\Actions\EditAction;
use function Livewire\wrap;// Tambahkan di bagian use statements

class PeminjamanRuanganResource extends Resource
{
    protected static ?string $model = PeminjamanRuangan::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $modelLabel = 'Peminjaman Ruangan';
    protected static ?string $navigationLabel = 'Peminjaman Ruangan';

    protected static ?string $navigationGroup = 'Peminjaman';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ruangan_id')
                    ->label('Ruangan')
                    ->options(Ruangan::query()
                        ->selectRaw("id, CONCAT(nama_ruangan, ' - ', tata_letak) as full_label")
                        ->pluck('full_label', 'id'))
                    ->required()
                    ->live()
                    ->searchable(),

                Forms\Components\TextInput::make('pegawai_name')
                    ->label('Pegawai')
                    ->disabled()
                    ->dehydrated()
                    ->default(fn() => auth('employee')->user()->name),

                Forms\Components\TextInput::make('kegiatan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->required()
                    ->rules([
                        'required',
                        'date',
                        'after_or_equal:today',
                        function (Get $get) {
                            return function (string $attribute, $value, Closure $fail) use ($get) {
                                $ruanganId = $get('ruangan_id');
                                $tanggalSelesai = $get('tanggal_selesai');
                                $waktuMulai = $get('waktu_mulai');
                                $waktuSelesai = $get('waktu_selesai');

                                if (!$ruanganId || !$tanggalSelesai || !$waktuMulai || !$waktuSelesai) {
                                    return;
                                }

                                $start = Carbon::parse($value . ' ' . $waktuMulai);
                                $end = Carbon::parse($tanggalSelesai . ' ' . $waktuSelesai);

                                if (!$start->lt($end)) {
                                    $fail('Waktu mulai harus sebelum waktu selesai');
                                    return;
                                }

                                if (!PeminjamanRuangan::isAvailable($ruanganId, $start, $end)) {
                                    $fail('Ruangan tidak tersedia pada tanggal dan waktu yang diminta');
                                }
                            };
                        }
                    ]),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->required()
                    ->rules([
                        'required',
                        'date',
                        'after_or_equal:tanggal_mulai'
                    ]),

                Forms\Components\TimePicker::make('waktu_mulai')
                    ->required()
                    ->seconds(false),

                Forms\Components\TimePicker::make('waktu_selesai')
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

                Forms\Components\Textarea::make('perlengkapan_tambahan')
                    ->label('Perlengkapan Tambahan')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('penanggung_jawab')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),

                Forms\Components\Hidden::make('pegawai_id')
                    ->default(fn () => auth('employee')->id())
                    ->dehydrated()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ruangan.nama_ruangan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ruangan.tata_letak')
                    ->label('Tata Letak')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kegiatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pegawai.name')
                    ->label('Pegawai')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date(),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->time(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->time(),

                Tables\Columns\TextColumn::make('penanggung_jawab')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('alasan_penolakan')
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
                    ->visible(fn (PeminjamanRuangan $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjamanRuangans::route('/'),
            'create' => Pages\CreatePeminjamanRuangan::route('/create'),
           // 'edit' => Pages\EditPeminjamanRuangan::route('/{record}/edit'),
        ];
    }
}