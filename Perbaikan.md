# Log Perbaikan & Bug Tracking — Sistem Monitoring Siswa PKL

Dokumentasi pelacakan bug, perbaikan visual, dan optimasi sistem sesuai kaidah Spec-Driven Development (SDD).

---

### [PRIORITY: MEDIUM]
- **Location:** [`resources/views/layouts/siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/layouts/siswa.blade.php) (Bottom Navigation Bar SVG Path Generator)
- **Severity:** Medium (Visual Glitch / Corner Clipping)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Pada fungsi pembentukan lintasan kurva SVG (`createNavPath` dan generator PHP `$initPath`), inisialisasi awal lintasan saat tab selain Tab 0 aktif dimulai dari koordinat `M {$r} 0` (tepi atas). Pada akhir kalkulasi, perintah penutup `L 0 {$r} Z` menghubungkan langsung titik `(0, r)` pada sisi kiri ke titik awal `(r, 0)` menggunakan garis lurus miring (*straight diagonal line*), sehingga menghasilkan sudut terpotong 45 derajat (*diagonal chamfer cut*) alih-alih sudut melengkung halus (*smooth quadratic bezier curve*).
- **Solution:**
  Memperbaiki titik awal (*origin path*) menjadi `M 0 {$r} Q 0 0 {$r} 0 L {$scoopStart} 0` pada seluruh kondisi tab aktif. Hal ini memastikan busur kurva sudut kiri-atas dieksekusi secara eksplisit dengan perintah `Q 0 0 {$r} 0`, dan penutup `Z` menyatu sempurna pada koordinat `(0, r)` tanpa memotong sudut.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: LOW]
- **Location:** [`app/Http/Controllers/RekapController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/RekapController.php) (Penyaringan Rentang Tanggal Absensi)
- **Severity:** Low (Query Boundary Edge Case pada SQLite)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Penggunaan klausa `whereBetween('tanggal', [$startDate, $endDate])` pada basis data SQLite menyebabkan nilai batas akhir tanggal (`$endDate` e.g. `'2026-09-23'`) gagal mencocokkan rekaman yang di-cast sebagai datetime/timestamp `'2026-09-23 00:00:00'` karena perbandingan leksikografis string.
- **Solution:**
  Mengganti klausa menjadi `whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)`. Fungsi `whereDate()` secara otomatis mengekstraksi bagian tanggal murni (`date(tanggal)`) sehingga konsisten dan akurat baik di lingkungan SQLite lokal maupun MySQL produksi.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: LOW]
- **Location:** [`resources/views/admin/detail_siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/detail_siswa.blade.php) (Line 143)
- **Severity:** Low (Hardcoded Name Glitch)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Nama siswa pada pesan status absensi kosong tertulis statis sebagai string `"Nathan Hall"` (`Siswa Nathan Hall belum melakukan selfie wajah...`) alih-alih merujuk objek siswa yang sedang ditinjau.
- **Solution:**
  Mengganti string statis dengan ekspresi Blade dinamis `{{ $siswa->name }}` sehingga nama siswa yang belum absen tampil secara akurat sesuai profil siswa yang bersangkutan.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: LOW]
- **Location:** [`resources/views/siswa/dashboard.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/dashboard.blade.php) & [`resources/views/siswa/profil.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/profil.blade.php)
- **Severity:** Low (Hardcoded Fallback Value)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Siswa baru yang belum dialokasikan ke industri mitra atau belum ditugaskan guru pembimbing menampilkan fallback hardcoded `"PT Telkom Indonesia"` dan `"Bapak Andi Pratama"`, sehingga menimbulkan distorsi informasi status penempatan siswa.
- **Solution:**
  Menyesuaikan fallback null coalescence menjadi label semantik informatif `"Belum Ditempatkan"` dan `"Belum Ditugaskan"`.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: LOW]
- **Location:** [`app/Models/Absensi.php`](file:///c:/Proyek%20Gua/WEx/app/Models/Absensi.php), [`resources/views/siswa/absensi.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/absensi.blade.php), [`resources/views/admin/rekap/index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/rekap/index.blade.php)
- **Severity:** Low (Storage URL Prefix Inconsistency)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Sebagian data seeder menyertakan prefiks `'storage/...'` sedangkan unggahan form baru menyimpan jalur relatif disk `'absensi/foto/...'`, berpotensi menghasilkan tautan ganda `storage/storage/...` jika dipanggil langsung dengan `asset('storage/' . $path)`.
- **Solution:**
  Menambahkan Eloquent accessor `foto_url` dan `ttd_url` pada model `Absensi` yang menormalisasi kedua format berkas secara transparan.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: LOW]
- **Location:** [`resources/views/siswa/absensi.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/absensi.blade.php) (Signature Canvas Resize Handler)
- **Severity:** Low (Canvas Buffer Clear on Mobile Resize / Rotation)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  Pemanggilan `canvas.width = rect.width` saat event `resize` mereset buffer 2D canvas sehingga menghapus goresan tanda tangan yang telah dibuat siswa jika orientasi perangkat berubah.
- **Solution:**
  Menambahkan pengecekan dimensi agar tidak mereset jika ukuran tidak berubah, serta mekanisme penyimpanan buffer sementara (`toDataURL` -> `drawImage`) sebelum redimensioning kanvas.
- **Target Deadline:** Immediate (Selesai pada 23 September 2026)

---

### [PRIORITY: MEDIUM]
- **Location:** [`app/Http/Controllers/RekapController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/RekapController.php) & [`resources/views/admin/rekap/index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/rekap/index.blade.php)
- **Severity:** Medium (CSV Text Clumping in Regional Excel & Lack of Styled SpreadsheetML Export)
- **Status:** Resolved (Terselesaikan)
- **Root Cause:**
  1. Fungsi `exportCsv` menggunakan pemisah bawaan koma (`,`) sehingga saat dibuka di Microsoft Excel dengan locale regional Indonesia (list separator titik koma `;`), seluruh kolom tergabung dalam sel A1 tanpa terbagi ke kolom A sampai L.
  2. Belum tersedianya opsi ekspor spreadsheet Excel berformat murni (`.xls` SpreadsheetML) dengan perataan warna, border, dan tata letak profesional seperti yang sudah diterapkan pada template impor akun siswa.
- **Solution:**
  1. Menyediakan endpoint ekspor resmi `admin.rekap.export-excel` berbasis SpreadsheetML XML dengan styling header teal institusional (`#008294`), border, penyesuaian lebar kolom otomatis, badge status kehadiran berwarna, dan formatting teks pada sel NISN.
  2. Memperbaiki `exportCsv` dengan menambahkan UTF-8 BOM (`\xEF\xBB\xBF`) dan menggunakan pemisah titik koma (`;`) yang kompatibel langsung dengan Microsoft Excel regional Indonesia.
  3. Memperbarui antarmuka pengguna pada halaman Rekapitulasi dengan tombol aksi yang serasi: *"Ekspor Excel (.xls)"* sebagai opsi utama dan *"Ekspor CSV"* sebagai opsi alternatif.
- **Target Deadline:** Immediate (Selesai pada 27 September 2026)
