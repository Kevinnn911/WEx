<?php
// File: app/Models/LaporanHarian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanHarian extends Model
{
    use HasFactory;

    protected $table = 'laporan_harian';

    protected $fillable = [
        'siswa_id',
        'absensi_id',
        'tanggal',
        'rencana_tugas',
        'catatan',
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

    public function absensi(): BelongsTo
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }
}
