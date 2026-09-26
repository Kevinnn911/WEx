<?php
// File: app/Models/TempatPkl.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempatPkl extends Model
{
    use HasFactory;

    protected $table = 'tempat_pkl';

    protected $fillable = [
        'nama_perusahaan',
        'bidang',
        'alamat',
        'kota',
        'kontak',
    ];

    public function siswa(): HasMany
    {
        return $this->hasMany(User::class, 'tempat_pkl_id');
    }
}
