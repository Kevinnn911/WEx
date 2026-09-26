<?php
// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\PeriodePkl;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Inisialisasi Periode PKL Utama
        PeriodePkl::firstOrCreate(
            ['nama_periode' => 'PKL Semester Ganjil 2026'],
            [
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2026-11-30',
                'is_aktif' => true,
            ]
        );

        // 2. Akun Administrator Utama Sekolah
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator PKL',
                'email' => 'admin@sekolah.sch.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nip' => '198001012005011001',
                'avatar' => null,
            ]
        );
    }
}
