<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes; // Opsional: untuk fitur soft delete


class Kendaraan extends Model
{
    //
    //use SoftDeletes; // Opsional

    protected $guarded = [];
    //protected $fillable = [
        //'jenis_kendaraan',
      //  'merk_kendaraan',
     //   'warna_kendaraan',
      //  'plat_nomer'
   // ];

    // Contoh relasi (jika diperlukan)
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanKendaraan::class);
    }

}
