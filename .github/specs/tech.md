# Technical Architecture Specification (`tech.md`) — Sistem Monitoring Siswa PKL

## 1. Arsitektur Umum
- **Framework:** Laravel 11 (PHP 8.3+)
- **Platform:** Web Application Responsive (Mobile-first untuk Siswa, Desktop-first untuk Guru & Admin)
- **Frontend Layer:** Blade Components + Tailwind CSS + Alpine.js / Vanilla JS
- **Design System:** Stitch Unified Product Design System (`Nunito Sans`, Material Symbols Outlined, Canvas `#F0F7FB`, Primary `#00626D` / `#0B7C8A`)
- **Database:** SQLite (default development) / MySQL (production ready via Eloquent ORM)
- **Penyimpanan Berkas:** Local Public Storage (`storage/app/public` linked to `public/storage`), modular abstraction untuk upload foto selfie absensi dan tanda tangan digital (PNG)
- **Autentikasi & Otorisasi:** Laravel Session-based Authentication dengan Role-Based Access Control (RBAC): `siswa`, `guru`, `admin`.

---

## 2. Struktur Database & Model

### 2.1 `users`
- `id` (PK, unsigned big integer)
- `name` (string)
- `username` (string, unique)
- `email` (string, unique, nullable)
- `password` (hashed string)
- `role` (enum: `'siswa'`, `'guru'`, `'admin'`)
- `nisn` (string, nullable)
- `nip` (string, nullable)
- `kelas` (string, nullable, e.g. `'XI TKJ 1'`)
- `jurusan` (string, nullable, e.g. `'Teknik Komputer dan Jaringan'`)
- `tempat_pkl_id` (FK nullable -> `tempat_pkl.id`)
- `guru_id` (FK nullable -> `users.id`)
- `avatar` (string, nullable)
- `timestamps`

### 2.2 `tempat_pkl`
- `id` (PK)
- `nama_perusahaan` (string)
- `bidang` (string, nullable)
- `alamat` (text, nullable)
- `kota` (string, nullable)
- `kontak` (string, nullable)
- `timestamps`

### 2.3 `periode_pkl`
- `id` (PK)
- `nama_periode` (string, e.g. `'PKL Semester Ganjil 2026'`)
- `tanggal_mulai` (date)
- `tanggal_selesai` (date)
- `is_aktif` (boolean, default true)
- `timestamps`

### 2.4 `absensi`
- `id` (PK)
- `siswa_id` (FK -> `users.id`)
- `tanggal` (date)
- `jam` (time)
- `foto_wajah` (string, path foto selfie)
- `tanda_tangan` (string, path file ttd PNG)
- `status` (enum: `'hadir'`, `'izin'`, `'sakit'`, default `'hadir'`)
- `timestamps`
- **Constraint Unik:** `unique(['siswa_id', 'tanggal'])` — siswa hanya dapat absen 1 kali per hari.

### 2.5 `laporan_harian`
- `id` (PK)
- `siswa_id` (FK -> `users.id`)
- `absensi_id` (FK nullable -> `absensi.id`)
- `tanggal` (date)
- `rencana_tugas` (text)
- `catatan` (text, nullable)
- `status` (enum: `'terkirim'`, `'pending'`, default `'terkirim'`)
- `timestamps`
- **Constraint Unik:** `unique(['siswa_id', 'tanggal'])`

---

## 3. Aturan Bisnis & Validasi Teknis

1. **FR-S-01 (Autentikasi):**
   - Siswa, Guru, dan Admin masuk melalui form login tunggal atau form terarah.
   - Redirect otomatis berdasarkan role: Siswa ke `/siswa/dashboard`, Guru/Admin ke `/admin/dashboard`.
2. **FR-S-03 & FR-S-04 (Absensi & Foto):**
   - Siswa wajib menyertakan foto wajah (selfie kamera) dan tanda tangan digital.
   - Tombol submit wajib disabled di sisi klien hingga foto dan ttd tersedia.
   - Sisi server memvalidasi format berkas gambar (JPEG/PNG/Base64) dan menyimpan ke storage publik.
   - Jika sudah pernah absen pada tanggal yang sama, sistem menolak submit duplikat.
3. **FR-S-06 (Laporan Harian):**
   - Siswa hanya dapat mengisi laporan jika telah berhasil melakukan absensi pada hari tersebut.
4. **Zero Emoji Policy:**
   - Dilarang menggunakan emoji atau karakter dekoratif unicode di dalam kode program, Blade views, JavaScript, CSS, dan komentar kode. Seluruh ikon wajib menggunakan ikon semantik Material Symbols Outlined atau SVG.
