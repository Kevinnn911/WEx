<?php
// File: app/Models/User.php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'nisn',
        'nip',
        'kelas',
        'jurusan',
        'tempat_pkl_id',
        'guru_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tempatPkl(): BelongsTo
    {
        return $this->belongsTo(TempatPkl::class, 'tempat_pkl_id');
    }

    public function guruPembimbing(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function siswaBimbingan(): HasMany
    {
        return $this->hasMany(User::class, 'guru_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    public function laporanHarian(): HasMany
    {
        return $this->hasMany(LaporanHarian::class, 'siswa_id');
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
