<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListKendaraan extends Model
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
        'keterangan'
    ];
    protected $table = 'peminjaman_kendaraans'; // atau nama tabel yang benar di database Anda
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Employee::class, 'pegawai_id');
    }
}
