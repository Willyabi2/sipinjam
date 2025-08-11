<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PeminjamanRuangan extends Model
{
    protected $fillable = [
        'ruangan_id',
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
        'keterangan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Employee::class, 'pegawai_id');
    }

    // Scope untuk cek ketersediaan ruangan
    public function scopeAvailable(Builder $query, $ruanganId, $startDateTime, $endDateTime)
    {
        return $query->where('ruangan_id', $ruanganId)
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
    public static function isAvailable($ruanganId, $startDateTime, $endDateTime)
    {
        return !self::available($ruanganId, $startDateTime, $endDateTime)->exists();
    }
}