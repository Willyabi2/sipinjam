<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    //
    protected $guarded = [];

        public function peminjaman()
    {
        return $this->hasMany(PeminjamanRuangan::class);
    }
}
