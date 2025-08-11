 <?php

/*namespace App\Filament\Admin\Resources;

/use App\Filament\Admin\Resources\PengajuanKendaraanResource\Pages;
/use App\Filament\Admin\Resources\PengajuanKendaraanResource\RelationManagers;
/use App\Models\PengajuanKendaraan;
/use Filament\Forms;
/use Filament\Forms\Form;
/use Filament\Resources\Resource;
/use Filament\Tables;
/use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanKendaraanResource extends Resource
{
    protected static ?string $model = PengajuanKendaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

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
                //
                TextColumn::make('user.name')->label('Pegawai'),
                TextColumn::make('kendaraan.nama_kendaraan'),
                TextColumn::make('tanggal_peminjaman')->date(),
                TextColumn::make('tanggal_pengembalian')->date(),
                TextColumn::make('keperluan')->limit(50),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
            Action::make('approve')
                ->button()
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function (PengajuanKendaraan $record) {
                    $record->update(['status' => 'approved']);
                    Notification::make()
                        ->title('Pengajuan Disetujui')
                        ->success()
                        ->send();
                })
                ->visible(fn (PengajuanKendaraan $record): bool => $record->status === 'pending'),

            Action::make('reject')
                ->button()
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->form([
                    Textarea::make('alasan_penolakan')
                        ->label('Alasan Penolakan')
                        ->required(),
                ])
                ->action(function (PengajuanKendaraan $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'alasan_penolakan' => $data['alasan_penolakan']
                    ]);
                    Notification::make()
                        ->title('Pengajuan Ditolak')
                        ->success()
                        ->send();
                })
                ->visible(fn (PengajuanKendaraan $record): bool => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('status', 'pending');
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
            'index' => Pages\ListPengajuanKendaraans::route('/'),
            'create' => Pages\CreatePengajuanKendaraan::route('/create'),
            'edit' => Pages\EditPengajuanKendaraan::route('/{record}/edit'),
        ];
    }
}
*/