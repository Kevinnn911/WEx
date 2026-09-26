<?php
// File: app/Models/Absensi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jam',
        'foto_wajah',
        'tanda_tangan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function laporan(): HasOne
    {
        return $this->hasOne(LaporanHarian::class, 'absensi_id');
    }

    /**
     * Accessor URL berkas foto selfie absensi.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto_wajah) {
            return null;
        }

        if (str_starts_with($this->foto_wajah, 'drive:')) {
            $fileId = substr($this->foto_wajah, 6);
            return 'https://lh3.googleusercontent.com/d/' . $fileId;
        }

        if (str_starts_with($this->foto_wajah, 'http')) {
            return $this->foto_wajah;
        }

        $cleanPath = str_starts_with($this->foto_wajah, 'storage/')
            ? $this->foto_wajah
            : 'storage/' . $this->foto_wajah;

        return asset($cleanPath);
    }

    /**
     * Accessor URL berkas tanda tangan digital absensi.
     */
    public function getTtdUrlAttribute(): ?string
    {
        if (!$this->tanda_tangan) {
            return null;
        }

        if (str_starts_with($this->tanda_tangan, 'drive:')) {
            $fileId = substr($this->tanda_tangan, 6);
            return 'https://lh3.googleusercontent.com/d/' . $fileId;
        }

        if (str_starts_with($this->tanda_tangan, 'http')) {
            return $this->tanda_tangan;
        }

        $cleanPath = str_starts_with($this->tanda_tangan, 'storage/')
            ? $this->tanda_tangan
            : 'storage/' . $this->tanda_tangan;

        return asset($cleanPath);
    }
}
