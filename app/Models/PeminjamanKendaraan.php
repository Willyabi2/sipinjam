<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Notifications\PeminjamanStatusNotification;
use Illuminate\Notifications\Notifiable;

class PeminjamanKendaraan extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'pegawai_id',
        'kegiatan',
        'status',
        'alasan_penolakan',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'perlengkapan_tambahan',
        'penanggung_jawab',
        'keterangan',
        'warna_kendaraan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Employee::class, 'pegawai_id');
    }

    // Scope untuk cek ketersediaan kendaraan
    public function scopeAvailable(Builder $query, $kendaraanId, $startDateTime, $endDateTime)
    {
        return $query->where('kendaraan_id', $kendaraanId)
            ->where('status', '!=', 'rejected')
            ->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->where(function ($innerQ) use ($startDateTime, $endDateTime) {
                    $innerQ->where('tanggal_mulai', '<', $endDateTime->format('Y-m-d'))
                          ->where('tanggal_selesai', '>', $startDateTime->format('Y-m-d'));
                })
                ->orWhere(function ($innerQ) use ($startDateTime, $endDateTime) {
                    $innerQ->whereDate('tanggal_mulai', '=', $startDateTime->format('Y-m-d'))
                          ->whereTime('waktu_selesai', '>', $startDateTime->format('H:i:s'))
                          ->whereTime('waktu_mulai', '<', $endDateTime->format('H:i:s'));
                });
            });
    }

    // Method untuk cek ketersediaan
    public static function isAvailable($kendaraanId, $startDateTime, $endDateTime)
    {
        return !self::available($kendaraanId, $startDateTime, $endDateTime)->exists();
    }

    // Auto-assign pegawai_id saat create
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->pegawai_id)) {
                $model->pegawai_id = auth('employee')->id() ?? 1;
            }
        });

        // Notifikasi saat status berubah
        static::updated(function ($peminjaman) {
            if ($peminjaman->wasChanged('status') && 
                in_array($peminjaman->status, ['approved', 'rejected'])) {
                $peminjaman->pegawai->notify(
                    new \App\Notifications\PeminjamanStatusNotification($peminjaman)
                );
            }
        });
    }
}