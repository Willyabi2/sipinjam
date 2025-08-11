<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Employee extends Authenticatable
{
    //
    use Notifiable;
    use HasFactory;

    protected $fillable = ['nip', 'name'];

    // Employee login tanpa password
    //public function getAuthPassword() {
    //    return null; // Karena login tanpa password
    //}
    public function validateForPassportPasswordGrant($password)
    {
        return true;
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanRuangan::class, 'pegawai_id');
    }
}
