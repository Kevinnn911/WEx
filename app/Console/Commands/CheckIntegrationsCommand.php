<?php
// File: app/Console/Commands/CheckIntegrationsCommand.php

namespace App\Console\Commands;

use App\Services\GoogleDriveService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckIntegrationsCommand extends Command
{
    /**
     * Nama dan signature perintah console.
     */
    protected $signature = 'app:check-integrations';

    /**
     * Deskripsi perintah console.
     */
    protected $description = 'Periksa status koneksi basis data Supabase (PostgreSQL) dan Google Drive Storage';

    /**
     * Eksekusi perintah console.
     */
    public function handle(GoogleDriveService $driveService): int
    {
        $this->info('--- PEMERIKSAAN KONEKSI INTEGRASI SISTEM ---');
        $this->newLine();

        // 1. Cek Basis Data (Supabase / SQLite)
        $this->line('[1] Memeriksa Koneksi Basis Data...');
        $driver = config('database.default');
        $this->line("    Driver aktif: {$driver}");

        try {
            DB::connection()->getPdo();
            $dbName = DB::connection()->getDatabaseName();
            $this->info("    [OK] Terhubung ke basis data: {$dbName}");

            $usersCount = DB::table('users')->count();
            $absensiCount = DB::table('absensi')->count();
            $laporanCount = DB::table('laporan_harian')->count();
            $this->line("    Statistik Data: {$usersCount} Pengguna, {$absensiCount} Absensi, {$laporanCount} Laporan Harian.");
        } catch (\Throwable $e) {
            $this->error("    [GAGAL] Gagal terhubung ke basis data: " . $e->getMessage());
        }

        $this->newLine();

        // 2. Cek Supabase API Credentials
        $this->line('[2] Memeriksa Konfigurasi Supabase API...');
        $supabaseUrl = config('services.supabase.url');
        if (empty($supabaseUrl)) {
            $this->comment('    [INFO] SUPABASE_URL belum diatur di .env (Opsional jika hanya menggunakan PostgreSQL direct connection).');
        } else {
            try {
                $response = Http::get($supabaseUrl);
                if ($response->status() < 500) {
                    $this->info("    [OK] Supabase URL dapat dijangkau ({$supabaseUrl}).");
                } else {
                    $this->warn("    [PERINGATAN] Supabase URL merespons dengan status: " . $response->status());
                }
            } catch (\Throwable $e) {
                $this->error("    [GAGAL] Gagal mengakses SUPABASE_URL: " . $e->getMessage());
            }
        }

        $this->newLine();

        // 3. Cek Google Drive Storage
        $this->line('[3] Memeriksa Konfigurasi Google Drive Storage...');
        $driveEnabled = config('services.google_drive.enabled');

        if (!$driveEnabled) {
            $this->comment('    [INFO] GOOGLE_DRIVE_ENABLED bernilai false.');
            $this->line('    Penyimpanan berkas saat ini dialihkan ke penyimpanan publik lokal (fallback otomatis aman).');
        } else {
            if (!$driveService->isConfigured()) {
                $this->warn('    [PERINGATAN] Google Drive diaktifkan tetapi kredensial (OAuth2 / Service Account) belum lengkap di .env.');
            } else {
                $this->info('    [OK] Kredensial Google Drive terkonfigurasi. Menguji pengambilan token akses...');
                try {
                    $reflection = new \ReflectionClass($driveService);
                    $method = $reflection->getMethod('getAccessToken');
                    $method->setAccessible(true);
                    $token = $method->invoke($driveService);

                    if ($token) {
                        $this->info('    [OK] Berhasil memperoleh Google Drive Access Token.');
                    } else {
                        $this->error('    [GAGAL] Gagal memperoleh Access Token. Periksa Refresh Token / Service Account JSON.');
                    }
                } catch (\Throwable $e) {
                    $this->error('    [GAGAL] Error saat menguji Google Drive: ' . $e->getMessage());
                }
            }
        }

        $this->newLine();
        $this->info('--- PEMERIKSAAN SELESAI ---');

        return Command::SUCCESS;
    }
}
