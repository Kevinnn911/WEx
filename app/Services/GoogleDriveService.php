<?php
// File: app/Services/GoogleDriveService.php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GoogleDriveService
{
    /**
     * Cek apakah konfigurasi Google Drive sudah aktif dan tersedia.
     */
    public function isConfigured(): bool
    {
        $enabled = config('services.google_drive.enabled', false);
        if (!$enabled) {
            return false;
        }

        $hasOAuth = !empty(config('services.google_drive.client_id'))
            && !empty(config('services.google_drive.client_secret'))
            && !empty(config('services.google_drive.refresh_token'));

        $hasServiceAccount = !empty(config('services.google_drive.service_account_json'));

        return $hasOAuth || $hasServiceAccount;
    }

    /**
     * Simpan media absensi (foto wajah atau tanda tangan digital).
     * Jika Google Drive aktif, berkas diunggah ke Google Drive sesuai hierarki yang diminta:
     * PKL / {Nama Pengguna} / {Bulan Tahun} / {Nama-Bulan-Tanggal-Tipe}.ext
     * Jika tidak aktif atau gagal, otomatis fallback ke public storage lokal.
     */
    public function storeAbsensiMedia(string $base64Data, string $type, User $siswa, string $tanggal): string
    {
        $decoded = $this->decodeBase64($base64Data);
        $binary = $decoded['binary'];
        $mime = $decoded['mime'];

        $dateObj = Carbon::parse($tanggal)->locale('id');
        $cleanStudentName = trim(preg_replace('/[\/\\\\:*?"<>|]/', '', $siswa->name ?: ('Siswa ' . $siswa->id)));
        $namaBulan = $dateObj->translatedFormat('F');
        $tgl = $dateObj->format('d');
        $typeLabel = $type === 'foto' ? 'Foto' : 'TTD';
        $extension = $type === 'foto' ? 'jpg' : 'png';

        // Format kode nama berkas: "Nama-Bulan-Tanggal-Tipe.ext"
        // Contoh: Nathan Pratama-September-26-Foto.jpg atau Nathan-September-26-TTD.png
        $fileName = "{$cleanStudentName}-{$namaBulan}-{$tgl}-{$typeLabel}.{$extension}";

        if ($this->isConfigured()) {
            try {
                $driveFileId = $this->uploadToDrive($binary, $fileName, $mime, $siswa, $tanggal);
                if ($driveFileId) {
                    return 'drive:' . $driveFileId;
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal mengunggah berkas absensi ke Google Drive, menggunakan penyimpanan lokal: ' . $e->getMessage());
            }
        }

        // Fallback: Simpan ke public storage lokal dengan format nama terstruktur
        $folder = $type === 'foto' ? 'absensi/foto' : 'absensi/ttd';
        $localName = "{$cleanStudentName}-{$namaBulan}-{$tgl}-{$typeLabel}-" . Str::random(6) . '.' . $extension;
        $localPath = $folder . '/' . $localName;

        Storage::disk('public')->put($localPath, $binary);

        return $localPath;
    }

    /**
     * Unggah berkas ke Google Drive API v3 dengan pembuatan hierarki folder otomatis:
     * Folder Root (PKL) -> Folder {Nama Pengguna} -> Folder {Bulan} -> File {Nama-Bulan-Tanggal}
     */
    private function uploadToDrive(string $binary, string $fileName, string $mime, User $siswa, string $tanggal): ?string
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return null;
        }

        $dateObj = Carbon::parse($tanggal)->locale('id');
        $namaBulanTahun = $dateObj->translatedFormat('F Y'); // Contoh: "September 2026"
        $cleanStudentName = trim(preg_replace('/[\/\\\\:*?"<>|]/', '', $siswa->name ?: ('Siswa ' . $siswa->id)));

        // 1. Tentukan Root Folder (Folder PKL dari konfigurasi Google Drive)
        $rootFolderId = config('services.google_drive.folder_id') ?: $this->getOrCreateFolder('PKL', null, $accessToken);

        // 2. Buat / Dapatkan Folder Nama Pengguna di dalam folder PKL
        $studentFolderId = $this->getOrCreateFolder($cleanStudentName, $rootFolderId, $accessToken);

        // 3. Buat / Dapatkan Folder Bulan (misal: "September 2026") di dalam folder Nama Pengguna
        $monthFolderId = $this->getOrCreateFolder($namaBulanTahun, $studentFolderId, $accessToken);

        // 4. Unggah berkas absensi harian langsung ke dalam folder Bulan tujuan
        $metadata = [
            'name' => $fileName,
            'parents' => [$monthFolderId],
        ];

        $boundary = '-------' . Str::random(24);
        $multipartBody = "--{$boundary}\r\n"
            . "Content-Type: application/json; charset=UTF-8\r\n\r\n"
            . json_encode($metadata) . "\r\n"
            . "--{$boundary}\r\n"
            . "Content-Type: {$mime}\r\n\r\n"
            . $binary . "\r\n"
            . "--{$boundary}--";

        $uploadResponse = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => 'multipart/related; boundary=' . $boundary,
                'Content-Length' => strlen($multipartBody),
            ])
            ->withBody($multipartBody, 'multipart/related; boundary=' . $boundary)
            ->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&fields=id,name,webViewLink');

        if (!$uploadResponse->successful()) {
            Log::error('Google Drive upload error: ' . $uploadResponse->body());
            return null;
        }

        $fileId = $uploadResponse->json('id');

        // 4. Jadikan berkas dapat diakses tautannya oleh publik/sekolah (anyone reader)
        if ($fileId) {
            $this->makeFilePublic($fileId, $accessToken);
        }

        return $fileId;
    }

    /**
     * Berikan izin baca publik agar pratinjau foto/ttd dapat dimuat pada sistem monitoring.
     */
    private function makeFilePublic(string $fileId, string $accessToken): void
    {
        try {
            Http::withToken($accessToken)
                ->post("https://www.googleapis.com/drive/v3/files/{$fileId}/permissions", [
                    'role' => 'reader',
                    'type' => 'anyone',
                ]);
        } catch (\Throwable $e) {
            Log::warning('Tidak dapat mengatur izin publik berkas Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Dapatkan folder ID yang ada atau buat baru jika belum ada di parent tertentu.
     */
    private function getOrCreateFolder(string $name, ?string $parentId, string $accessToken): string
    {
        $cacheKey = 'gdrive_folder_' . md5(($parentId ?? 'root') . '_' . $name);
        $cachedId = Cache::get($cacheKey);
        if ($cachedId) {
            return $cachedId;
        }

        $query = "mimeType = 'application/vnd.google-apps.folder' and name = '{$name}' and trashed = false";
        if ($parentId) {
            $query .= " and '{$parentId}' in parents";
        }

        $searchResponse = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', [
                'q' => $query,
                'fields' => 'files(id, name)',
                'pageSize' => 1,
            ]);

        if ($searchResponse->successful()) {
            $files = $searchResponse->json('files');
            if (!empty($files) && isset($files[0]['id'])) {
                Cache::put($cacheKey, $files[0]['id'], 3600);
                return $files[0]['id'];
            }
        }

        // Buat folder baru jika belum ada
        $payload = [
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ];
        if ($parentId) {
            $payload['parents'] = [$parentId];
        }

        $createResponse = Http::withToken($accessToken)
            ->post('https://www.googleapis.com/drive/v3/files', $payload);

        if ($createResponse->successful()) {
            $folderId = $createResponse->json('id');
            Cache::put($cacheKey, $folderId, 3600);
            return $folderId;
        }

        throw new \RuntimeException('Gagal membuat folder Google Drive: ' . $createResponse->body());
    }

    /**
     * Dapatkan Google OAuth2 Access Token (mendukung Refresh Token & Service Account JWT).
     */
    private function getAccessToken(): ?string
    {
        return Cache::remember('google_drive_access_token', 3000, function () {
            // Mode 1: OAuth2 Refresh Token
            $refreshToken = config('services.google_drive.refresh_token');
            $clientId = config('services.google_drive.client_id');
            $clientSecret = config('services.google_drive.client_secret');

            if ($refreshToken && $clientId && $clientSecret) {
                $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $refreshToken,
                    'grant_type' => 'refresh_token',
                ]);

                if ($response->successful()) {
                    return $response->json('access_token');
                }

                Log::error('Google Drive OAuth token refresh failed: ' . $response->body());
            }

            // Mode 2: Service Account JSON
            $saJson = config('services.google_drive.service_account_json');
            if ($saJson) {
                return $this->getServiceAccountToken($saJson);
            }

            return null;
        });
    }

    /**
     * Buat Google OAuth2 token menggunakan Service Account JSON key.
     */
    private function getServiceAccountToken(string $saJson): ?string
    {
        $credentials = null;
        if (file_exists($saJson)) {
            $credentials = json_decode(file_get_contents($saJson), true);
        } else {
            $credentials = json_decode($saJson, true);
        }

        if (!$credentials || empty($credentials['client_email']) || empty($credentials['private_key'])) {
            Log::error('Format Google Service Account JSON tidak valid.');
            return null;
        }

        $now = time();
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $claim = [
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/drive',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $encodedHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
        $encodedClaim = rtrim(strtr(base64_encode(json_encode($claim)), '+/', '-_'), '=');
        $signatureInput = $encodedHeader . '.' . $encodedClaim;

        $signature = '';
        $privateKey = openssl_pkey_get_private($credentials['private_key']);
        if (!$privateKey) {
            Log::error('Private key Google Service Account tidak dapat dibaca oleh OpenSSL.');
            return null;
        }

        openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $encodedSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
        $jwt = $signatureInput . '.' . $encodedSignature;

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json('access_token');
        }

        Log::error('Google Service Account JWT exchange failed: ' . $response->body());
        return null;
    }

    /**
     * Parsing dan decode data URI Base64.
     *
     * @return array{binary: string, mime: string}
     */
    private function decodeBase64(string $base64Data): array
    {
        $mime = 'image/jpeg';
        if (preg_match('/^data:(image\/\w+);base64,/', $base64Data, $matches)) {
            $mime = $matches[1];
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
        }

        $binary = base64_decode($base64Data) ?: '';

        return [
            'binary' => $binary,
            'mime' => $mime,
        ];
    }
}
