<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\PeminjamanStatusNotification;


class ListRuangan extends Model
{
    //
    use Notifiable;
            public function ruangan()
        {
            return $this->belongsTo(Ruangan::class);
        }

        public function pegawai()
        {
            return $this->belongsTo(Employee::class, 'pegawai_id');
        }
        protected $fillable = [
            // ... field lainnya
            'ruangan_id',
            'pegawai_id',
            'kegiatan',  // <-- Tambahkan ini
            'status',
            'alasan_penolakan',
            'tanggal_mulai',  // <-- Tambahkan ini
            'tanggal_selesai',
            'waktu_mulai',
            'waktu_selesai',
            'perlengkapan_tambahan',
            'penanggung_jawab',
            'keterangan',
        ];
        protected $attributes = [
    'pegawai_id' => 1 // Ganti dengan ID pegawai default
        ];
        protected $table = 'peminjaman_ruangans'; // atau nama tabel yang benar di database Anda
        
}
