# Analysis Report — Sistem Monitoring Siswa PKL

Laporan analisis dan dokumentasi implementasi fitur secara berkala sesuai dengan kaidah Spec-Driven Development (SDD).

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Implementasi Halaman Login Siswa (Mobile-First)
- **Technical Implementation:**
  - Membuat halaman antarmuka mandiri [`stitch_unified_product_design_system/login_siswa/code.html`](file:///c:/Proyek%20Gua/WEx/stitch_unified_product_design_system/login_siswa/code.html) mengacu pada spesifikasi PRD FR-S-01 dan Prompt 01.
  - Menerapkan arsitektur desain yang identik dengan [`dashboard_siswa/code.html`](file:///c:/Proyek%20Gua/WEx/stitch_unified_product_design_system/dashboard_siswa/code.html):
    - Container mobile frame `max-w-[480px]` dengan latar belakang canvas off-white (`#F0F7FB`).
    - Top atmosphere sky gradient (`h-80 bg-gradient-to-b from-[#BAE8F8] via-[#DCF3FB] to-transparent`).
    - Tipografi konsisten **Nunito Sans** dan ikonografi **Material Symbols Outlined** (mematuhi Zero Emoji Policy).
    - Form card `rounded-2xl bg-white border border-border-hairline shadow-xs` dengan input min-height 44px dan toggle password visibility.
    - CTA button primer dengan gradien teal (`from-[#00626D] to-[#0A8597]`), efek elevasi, dan transisi `active:scale-95`.
    - Alert error inline berbasis semantik status error (`#B42318` + latar merah soft).
    - Penambahan fitur demo cepat "Isi Otomatis (Nathan)" dan redirect mulus ke `../dashboard_siswa/code.html`.
- **Impact:**
  - Siswa memiliki alur masuk yang intuitif, aman, ramah pengguna, dan responsif.
  - Menjaga kesinambungan visual 100% dengan dashboard siswa tanpa diskrepansi desain.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Implementasi Curved Floating Bottom Navigation Bar (Siswa Mobile)
- **Technical Implementation:**
  - Memperbarui komponen navigasi bawah pada [`resources/views/layouts/siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/layouts/siswa.blade.php) sesuai referensi desain *Curved Outside Bottom Navigation Bar*.
  - Menyediakan 5 tab navigasi lengkap sesuai alur spesifikasi produk (`.github/specs/product.md`) dan rute aplikasi (`routes/web.php`):
    1. **Beranda** (`route('siswa.dashboard')`, icon: `home`)
    2. **Absensi** (`route('siswa.absensi')`, icon: `photo_camera`)
    3. **Laporan** (`route('siswa.laporan')`, icon: `assignment`)
    4. **Riwayat** (`route('siswa.riwayat')`, icon: `history`)
    5. **Profil** (`route('siswa.profil')`, icon: `person`)
  - Menghasilkan latar belakang kurva lekukan dinamis (*scoop notch*) menggunakan kalkulasi jalur vektor SVG (`<path>` dengan Bezier cubic curve) yang presisi di sisi server (SSR) dan responsif saat viewport mengalami perubahan ukuran (*resize listener*).
  - Merancang tombol aktif melayang (*floating active circle*) dengan diameter 54px, elevasi di atas batas atas navbar, border aksen primer `#008294`, ikon berukuran 26px, dan label teks tebal (*font-extrabold* `#0E2933`).
  - Menambahkan *drag indicator bar* di bagian bawah navigasi untuk nuansa antarmuka mobile modern ala iOS.
  - Menyesuaikan *padding bottom* pada area konten utama (`pb-32`) agar seluruh informasi halaman tidak tertutup oleh navigasi melayang.
- **Impact:**
  - Pengalaman navigasi pengguna siswa menjadi sangat modern, elegan, dan interaktif sesuai dengan desain referensi yang diminta.
  - Akses menuju seluruh fitur esensial siswa (termasuk pengisian Laporan Harian) kini dapat diakses langsung dalam 1 ketukan jari.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Penggantian Identitas Visual & Branding Logo Sekolah (`Logo1.png`)
- **Technical Implementation:**
  - Menyalin berkas logo resmi [`Logo/Logo1.png`](file:///c:/Proyek%20Gua/WEx/Logo/Logo1.png) (SMK Plus Pelita Nusantara) ke direktori publik [`public/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/Logo1.png), [`public/images/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/images/Logo1.png), serta menjadikannya sebagai favicon aplikasi ([`public/favicon.ico`](file:///c:/Proyek%20Gua/WEx/public/favicon.ico) & [`public/favicon.png`](file:///c:/Proyek%20Gua/WEx/public/favicon.png)).
  - Mengganti seluruh ikon placeholder sekolah (`school`) pada halaman login ([`resources/views/auth/login.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/auth/login.blade.php)) dengan logo asli `Logo1.png` dalam kartu berbingkai halus (`w-20 h-20 p-2 rounded-2xl`).
  - Memperbarui sidebar brand header pada tata letak admin ([`resources/views/layouts/admin.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/layouts/admin.blade.php)) menggunakan `Logo1.png` dan nama resmi institusi **SMK Plus Pelita Nusantara**.
  - Menambahkan tautan *shortcut icon / favicon* resmi di seluruh tata letak utama (`layouts/siswa.blade.php`, `layouts/admin.blade.php`, dan `auth/login.blade.php`).
- **Impact:**
  - Meningkatkan kredibilitas dan identitas resmi aplikasi sekolah SMK Plus Pelita Nusantara.
  - Seluruh pengguna (siswa, guru, dan admin) disajikan lambang resmi institusi yang konsisten di berbagai perangkat.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Modul Kelola Master Data PKL, Manajemen Akun (Siswa, Guru, Admin) & Rekapitulasi Ekspor (FR-A-05, FR-A-06 & Reporting)
- **Technical Implementation:**
  - **Kelola Tempat PKL Industri (`TempatPklController.php` & Views):**
    - Menyediakan operasi CRUD penuh (Index, Create, Store, Edit, Update, Destroy) untuk perusahaan mitra.
    - Dilengkapi pencarian nama perusahaan, bidang, dan kota, serta validasi proteksi penghapusan jika perusahaan masih memiliki siswa aktif.
  - **Kelola Siswa & Penempatan (`SiswaKelolaController.php` & Views):**
    - Menyediakan pendaftaran dan pembaruan siswa lengkap dengan NISN, nama, kelas, jurusan, serta penempatan industri mitra dan guru pembimbing.
    - Dilengkapi fitur pencarian terpadu dan filter dropdown berdasarkan tempat PKL dan pembimbing.
  - **Kelola Guru Pembimbing (`GuruKelolaController.php` & Views):**
    - Menyediakan manajemen akun guru, NIP, email, dan username, serta pemantauan jumlah siswa bimbingan masing-masing guru.
  - **Kelola Akun Administrator (`AdminKelolaController.php` & Views):**
    - Menyediakan manajemen akun admin sekolah dengan proteksi akun aktif diri sendiri dan kuota minimum 1 admin sistem.
  - **Kelola Periode PKL (`PeriodePklController.php` & Views):**
    - Menyediakan manajemen jadwal semester PKL dan sistem aktivasi satu periode utama (`setAktif`).
  - **Rekapitulasi & Ekspor Laporan (`RekapController.php` & Views):**
    - Halaman rekapitulasi presensi dan tugas harian siswa dengan filter fleksibel (rentang tanggal, kelas, tempat PKL, status).
    - Fitur unduh file **CSV** standar RFC 4180 dengan UTF-8 BOM untuk kompatibilitas langsung di Microsoft Excel.
    - Fitur **Cetak Dokumen Resmi A4** lengkap dengan Kop Surat resmi SMK Plus Pelita Nusantara, logo `Logo1.png`, tabel kehadiran, dan kolom pengesahan tanda tangan Koordinator Hubin dan Kepala Sekolah.
  - **Sidebar & Keamanan RBAC (`layouts/admin.blade.php` & `routes/web.php`):**
    - Integrasi seluruh menu master data ke sidebar admin dengan proteksi otorisasi berbasis role (`role:admin` untuk data master, `role:guru,admin` untuk dashboard dan rekap).
  - **Pengujian Otomatis (`tests/Feature/PklMonitoringTest.php`):**
    - Menambahkan 7 unit/feature test komprehensif, dengan total 20 tests dan 120 assertions yang seluruhnya berstatus hijau (100% lulus).
- **Impact:**
  - Seluruh cakupan fungsional MVP (FR-S-01 s.d. FR-S-08, FR-A-01 s.d. FR-A-06, dan pelaporan ekspor sekolah) kini telah rampung 100% secara fungsional, teruji, dan siap digunakan.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Audit Komprehensif Seluruh Sistem, Pencegahan Bug Edge Cases & Verifikasi Kualitas Produksi
- **Technical Implementation:**
  - Menjalankan audit sintaks PHP (`php -l`) di seluruh controller, model, migration, seeder, dan routing dengan hasil 0 syntax error.
  - Kompilasi cache template Blade (`php artisan view:cache`) dan routing (`php artisan route:cache`) 100% sukses tanpa error.
  - Mengaudit seluruh 86 pemanggilan `route(...)` di semua file Blade untuk memastikan tidak ada nama rute yang hilang atau rusak.
  - Menambahkan Eloquent accessor `foto_url` dan `ttd_url` pada model `Absensi` guna menormalisasi format penyimpanan berkas (baik relatif maupun dengan prefiks storage) sehingga pratinjau bukti selfie dan tanda tangan selalu valid.
  - Menghilangkan nama statis hardcoded pada tampilan peninjauan absensi kosong (`admin/detail_siswa.blade.php`), menggantikannya dengan nama dinamis siswa.
  - Menghapus fallback statis tempat PKL dan guru pembimbing pada dashboard dan profil siswa, menggantikannya dengan label semantik informatif `"Belum Ditempatkan"` dan `"Belum Ditugaskan"`.
  - Mengoptimalkan penanganan resize kanvas tanda tangan digital pada tampilan mobile agar tidak menghapus goresan tanda tangan saat orientasi layar diputar.
  - Mengonfirmasi kepatuhan penuh terhadap **Zero Emoji Policy** di seluruh file PHP, Blade, JavaScript, dan CSS.
  - Memperluas automated test suite hingga 23 automated tests (130 assertions) dengan tingkat kelulusan 100%.
- **Impact:**
  - Sistem memiliki ketahanan tinggi terhadap skenario edge case (seperti siswa baru tanpa penempatan, perubahan orientasi gawai mobile, dan data path lawas).
  - Aplikasi berada dalam status stabil, bersih, dan siap untuk rilis produksi.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Pembersihan Total Data Dummy/Mock dan Arsitektur Integrasi Supabase (PostgreSQL) serta Google Drive Storage
- **Technical Implementation:**
  - Menghapus komponen tombol & generator tiruan "Mock Selfie" pada antarmuka absensi siswa ([`resources/views/siswa/absensi.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/absensi.blade.php)), menggantikannya dengan penanganan kamera live murni dan opsi ambil/unggah foto asli dari gawai.
  - Menghilangkan panel pengalih akun percobaan "Akun Percobaan (Demo Satu-Klik)" dan fungsi JavaScript `fillDemo()` pada antarmuka autentikasi ([`resources/views/auth/login.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/auth/login.blade.php)).
  - Merestrukturisasi seeder basis data ([`database/seeders/DatabaseSeeder.php`](file:///c:/Proyek%20Gua/WEx/database/seeders/DatabaseSeeder.php)) untuk membersihkan seluruh akun siswa dummy (Nathan, Siti, Budi), riwayat absensi palsu, berkas transparan 1x1 PNG seed dummy, serta laporan harian palsu. Hanya menyisakan inisialisasi akun Administrator sekolah dan jadwal periode aktif awal.
  - Menghapus folder dan berkas fisik `storage/app/public/seed/` serta mengeksekusi `migrate:fresh --seed` sehingga tabel data absensi dan laporan harian berada pada status bersih nol data dummy.
  - Mengaktifkan ekstensi `pdo_pgsql` dan `pgsql` pada konfigurasi PHP lingkungan eksekusi untuk mendukung koneksi native PostgreSQL ke Supabase.
  - Membangun [`GoogleDriveService`](file:///c:/Proyek%20Gua/WEx/app/Services/GoogleDriveService.php) dengan dukungan OAuth2 Refresh Token dan Google Service Account JWT, pembuatan struktur hierarki folder otomatis `PKL-MONITORING / {Year} / {Student-Name} / {Year-Month} / {Year-Month-Day}`, serta mekanisme fallback otomatis ke penyimpanan lokal jika kredensial Drive belum aktif.
  - Memperbarui model [`Absensi`](file:///c:/Proyek%20Gua/WEx/app/Models/Absensi.php) dengan accessor `foto_url` dan `ttd_url` yang mendukung format Google Drive ID (`drive:{id}`).
  - Menambahkan artisan command `php artisan app:check-integrations` ([`app/Console/Commands/CheckIntegrationsCommand.php`](file:///c:/Proyek%20Gua/WEx/app/Console/Commands/CheckIntegrationsCommand.php)) untuk memvalidasi status koneksi Supabase dan Google Drive kapan saja.
  - Memperbarui variabel lingkungan [`.env`](file:///c:/Proyek%20Gua/WEx/.env) dan [`.env.example`](file:///c:/Proyek%20Gua/WEx/.env.example) dengan template konfigurasi Supabase dan Google Drive yang terstandardisasi.
- **Impact:**
  - Basis data dan antarmuka aplikasi kini 100% bersih dari data mock/dummy dan siap digunakan untuk data operasional riil sekolah.
  - Sistem memiliki jembatan integrasi siap pakai (*turnkey integration*) untuk Supabase dan Google Drive tanpa risiko downtime karena didukung *safe fallback mechanism*.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Konfigurasi & Aktivasi Kredensial Produksi Supabase API dan Google Drive Storage (OAuth2)
- **Technical Implementation:**
  - Mengonfigurasi kredensial Google Drive OAuth2 ke dalam file [`.env`](file:///c:/Proyek%20Gua/WEx/.env) (`GOOGLE_DRIVE_CLIENT_ID`, `GOOGLE_DRIVE_CLIENT_SECRET`, `GOOGLE_DRIVE_REFRESH_TOKEN`, dan `GOOGLE_DRIVE_FOLDER_ID`).
  - Mengaktifkan `GOOGLE_DRIVE_ENABLED=true` sehingga seluruh unggahan swafoto absensi dan tanda tangan digital siswa secara otomatis diarahkan ke folder Google Drive tujuan.
  - Mengonfigurasi kredensial Supabase API (`SUPABASE_URL`, `SUPABASE_PUBLISHABLE_KEY`, `SUPABASE_SECRET_KEY`, `SUPABASE_JWKS_URL`) dan menambahkan fallback otomatis pada [`config/services.php`](file:///c:/Proyek%20Gua/WEx/config/services.php).
  - Melakukan verifikasi koneksi end-to-end melalui perintah `php artisan app:check-integrations` dengan hasil:
    - Supabase API: Terhubung dan dapat dijangkau (`200 OK`).
    - Google Drive API: Berhasil melakukan pertukaran token dan memperoleh Access Token aktif dari Google.
- **Impact:**
  - Sistem penyimpanan berkas absensi kini telah terhubung ke cloud storage Google Drive resmi.
  - Siap untuk proses operasional absensi dengan reliabilitas tinggi.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Pembuatan Hierarki Folder Otomatis Google Drive (`PKL / Nama Pengguna / Bulan`) & Penamaan Berkas `Nama-Bulan-Tanggal`
- **Technical Implementation:**
  - Memperbarui logika [`app/Services/GoogleDriveService.php`](file:///c:/Proyek%20Gua/WEx/app/Services/GoogleDriveService.php) agar otomatis membuat hierarki berjenjang di dalam folder PKL:
    1. **Folder Nama Siswa:** Dibuat otomatis saat pengiriman data pertama (misal: `Nathan Pratama`).
    2. **Folder Bulan:** Dibuat otomatis di dalam folder siswa (misal: `September 2026`).
    3. **Berkas Harian:** Disimpan langsung di dalam folder bulan dengan format penamaan kode yang terstruktur:
       - Foto Selfie: `{Nama}-{Bulan}-{Tanggal}-Foto.jpg` (contoh: `Nathan Pratama-September-26-Foto.jpg`).
       - Tanda Tangan: `{Nama}-{Bulan}-{Tanggal}-TTD.png` (contoh: `Nathan Pratama-September-26-TTD.png`).
  - Menerapkan mekanisme pembersihan karakter ilegal pada nama berkas serta sinkronisasi penamaan pada penyimpanan lokal *fallback*.
  - Melakukan verifikasi dengan suite pengujian otomatis (`php artisan test`), seluruh 23 tests lulus 100%.
- **Impact:**
  - Data Google Drive terorganisasi dengan sangat rapi, terstruktur per siswa dan per bulan tanpa perlu pembuatan folder manual oleh guru atau admin.
  - Berkas absensi harian mudah dicari dan diidentifikasi secara visual langsung dari Google Drive.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Standardisasi Zona Waktu Aplikasi (`Asia/Jakarta` - WIB) & Penyesuaian Pratinjau Bukti Google Drive di Panel Admin
- **Technical Implementation:**
  - Mengonfigurasi `APP_TIMEZONE=Asia/Jakarta` pada [`.env`](file:///c:/Proyek%20Gua/WEx/.env), [`.env.example`](file:///c:/Proyek%20Gua/WEx/.env.example), dan [`config/app.php`](file:///c:/Proyek%20Gua/WEx/config/app.php) menggantikan zona default `UTC`.
  - Mengoreksi data absensi lampau yang terekam pada jam server UTC (03:08) menjadi waktu lokal Indonesia Barat yang tepat (10:08 WIB).
  - Memperbarui pemanggilan pratinjau foto selfie dan tanda tangan di antarmuka Admin ([`resources/views/admin/dashboard.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/dashboard.blade.php) dan [`resources/views/admin/detail_siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/detail_siswa.blade.php)) agar menggunakan accessor model `foto_url` dan `ttd_url` sehingga berkas yang tersimpan di Google Drive dapat dipratinjau secara langsung tanpa error 404 lokal.
  - Menjalankan 23 automated tests dengan tingkat kelulusan 100%.
- **Impact:**
  - Jam presensi siswa kini akurat secara real-time mengikuti zona waktu WIB (Indonesia Barat).
  - Admin dan guru pembimbing dapat melihat pratinjau bukti absensi Google Drive langsung dari dashboard pemantauan.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Integrasi Identitas Visual Developer (`logo2.png`) pada Halaman Login & Profil Siswa
- **Technical Implementation:**
  - Menyalin aset developer logo [`public/logo2.png`](file:///c:/Proyek%20Gua/WEx/public/logo2.png) (ikon Pegasus The Beyonders) ke direktori publik aplikasi.
  - **Opsi 2 (Footer Login):** Memperbarui footer pada [`resources/views/auth/login.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/auth/login.blade.php) dengan kapsul identitas pengembang (*brand capsule*) berlatar putih/soft-blur, badge circular warm sand `#FAF5EE`, teks *"Crafted & Developed by The Beyonders Development"*, serta keterangan institusi sekolah secara harmonis dan tidak menimpa logo sekolah di kartu login.
  - **Opsi 3 (Profil Siswa):** Memperbarui [`resources/views/siswa/profil.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/profil.blade.php) dengan kartu identitas developer elegan bertema gradien lembut (`from-white to-[#FAF5EE]`), border `#EADBCE`, badge resmi *"Official Developer"*, dan informasi versi rilis stabil `v1.0.0 Stable`.
  - Memastikan kepatuhan total pada **Zero Emoji Policy** dan memvalidasi kelulusan seluruh 23 automated tests (100% pass).
- **Impact:**
  - Identitas pengembang perangkat lunak (*The Beyonders*) tampil secara eksklusif, profesional, dan proporsional tanpa mengurangi atau menutupi lambang resmi sekolah.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Refinement Estetika Identitas Developer: Penghapusan Badge Login & Harmonisasi Tema Profil Siswa
- **Technical Implementation:**
  - **Halaman Login ([`resources/views/auth/login.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/auth/login.blade.php)):** Menghapus kapsul melayang logo developer sesuai permintaan pengguna, mengembalikan footer login ke format teks minimalis resmi sekolah (*"SMK Plus Pelita Nusantara — Sistem Monitoring PKL &bull; Versi WEx 1.0"*).
  - **Halaman Profil Siswa ([`resources/views/siswa/profil.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/profil.blade.php)):** 
    - Menghilangkan warna emas/krem yang terlalu kontras (`bg-[#FAF5EE]`, `#EADBCE`, `#8C5824`).
    - Menyelaraskan kartu identitas pengembang dengan *design system* utama aplikasi: latar putih bersih (`bg-white`), bingkai hairline standar (`border-border-hairline`), wadah ikon bernuansa icy soft (`bg-[#FAFDFE]`), serta *chip badge* warna khas teal (`bg-primary-soft text-primary`).
  - Menjalankan 23 automated tests dengan tingkat kelulusan 100%.
- **Impact:**
  - Tampilan halaman profil kini 100% harmonis, serasi, dan menyatu alami dengan seluruh kartu antarmuka tanpa diskrepansi warna.
  - Halaman login kembali bersih dan berfokus penuh pada identitas sekolah.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Rekonstruksi Vektor Siluet Logo Developer (`logo2.png`) ke Warna Charcoal Black (#0E2933)
- **Technical Implementation:**
  - Memproses berkas grafis [`public/logo2.png`](file:///c:/Proyek%20Gua/WEx/public/logo2.png) dan [`Logo/logo2.png`](file:///c:/Proyek%20Gua/WEx/Logo/logo2.png) menggunakan PHP GD engine dengan mempertahankan 100% kanal transparansi alfa (*anti-aliased alpha channel*).
  - Mengubah piksel grafis Pegasus yang sebelumnya berwarna krem pucat/emas menjadi siluet hitam pekat/charcoal (*deep slate* `#0E2933`).
  - Menambahkan *cache-busting query parameter* (`?v={{ filemtime(...) }}`) pada tag `<img>` di [`resources/views/siswa/profil.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/profil.blade.php) agar browser pengguna langsung memuat gambar baru tanpa tertahan cache lawas.
  - Memvalidasi stabilitas dengan 23 automated tests (100% pass).
- **Impact:**
  - Logo Pegasus developer kini tampak sangat tegas, jelas, kontras, dan tajam di atas latar kartu putih tanpa terlihat pudar atau menyilaukan.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Peniadaan Fitur Tanda Tangan Digital pada Alur Absensi Siswa
- **Technical Implementation:**
  - **Skema Database:**
    - Membuat dan menjalankan migrasi [`database/migrations/2026_09_26_131059_make_tanda_tangan_nullable_in_absensi_table.php`](file:///c:/Proyek%20Gua/WEx/database/migrations/2026_09_26_131059_make_tanda_tangan_nullable_in_absensi_table.php) sehingga kolom `tanda_tangan` bersifat nullable (tanpa menghapus riwayat data absensi lampau).
    - Memperbarui definisi tabel pada [`database/migrations/0001_01_01_000004_create_absensi_table.php`](file:///c:/Proyek%20Gua/WEx/database/migrations/0001_01_01_000004_create_absensi_table.php).
  - **Backend Controller ([`app/Http/Controllers/SiswaController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/SiswaController.php)):**
    - Menghapus aturan validasi `required` untuk `tanda_tangan` pada method `submitAbsensi()`.
    - Presensi kini hanya mewajibkan foto selfie wajah (`foto_wajah`).
    - Penyimpanan file tanda tangan disetel opsional/kondisional (hanya diproses jika data tanda tangan dikirimkan).
  - **Antarmuka Presensi Siswa ([`resources/views/siswa/absensi.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/absensi.blade.php)):**
    - Menghapus indikator 2 langkah (*step indicator*).
    - Menghapus field form tersembunyi `inputTandaTangan`.
    - Menghapus komponen kanvas tanda tangan digital (*signature canvas card*) dan tombol hapus/reset tanda tangan.
    - Menyederhanakan validasi interaktif di browser: tombol *"Kirim Presensi Sekarang"* langsung aktif seketika setelah foto selfie diambil.
    - Menyesuaikan tampilan konfirmasi kehadiran: jika riwayat absensi tidak memiliki tanda tangan, pratinjau foto selfie tampil di tengah dengan proporsi kartu yang ideal.
  - **Dashboard Siswa ([`resources/views/siswa/dashboard.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/siswa/dashboard.blade.php)):**
    - Memperbarui teks judul ajakan absensi menjadi *"Ambil Foto Selfie Presensi"* dan deskripsi petunjuk agar tidak lagi menyebut tanda tangan digital.
    - Memperbarui kartu menu aktivitas dari *"Selfie & Tanda Tangan"* menjadi *"Foto Selfie Kamera"*.
  - **Antarmuka Admin & Rekapitulasi ([`resources/views/admin/dashboard.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/dashboard.blade.php), [`resources/views/admin/rekap/index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/rekap/index.blade.php), [`resources/views/admin/detail_siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/detail_siswa.blade.php)):**
    - Mengubah tajuk kolom tabel menjadi *"Bukti Foto Presensi"*.
    - Menampilkan thumbnail tanda tangan secara kondisional hanya bila data riwayat lampau memiliki tanda tangan, sehingga data presensi baru tetap bersih dan tidak menampilkan wadah kosong.
    - Memperbarui keterangan peninjauan kehadiran siswa.
  - **Automated Testing ([`tests/Feature/PklMonitoringTest.php`](file:///c:/Proyek%20Gua/WEx/tests/Feature/PklMonitoringTest.php)):**
    - Menambahkan pengujian `test_siswa_can_submit_attendance_with_photo_only_without_signature`.
    - Menjalankan suite pengujian lengkap dengan 24 tests dan 134 assertions (100% pass).
- **Impact:**
  - Proses absensi harian siswa menjadi jauh lebih cepat, ringkas, dan praktis tanpa friksi mengisi goresan tanda tangan digital di layar sentuh ponsel.
---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Perbaikan Transparansi Penuh Latar Belakang Logo Sekolah (`Logo1.png` & Favicon)
- **Technical Implementation:**
  - **Identifikasi Akar Masalah (*Root Cause Analysis*):**
    - Berkas grafis asli `Logo1.png` memiliki kanal alfa semi-transparan (`alpha: 63` pada skala 0–127 GD, atau ~50% opasitas warna putih) di area luar perisai lambang sekolah (sebanyak 24.889 dari 40.000 piksel).
    - Akibatnya, saat berkas ditampilkan di atas latar belakang gelap (seperti tab browser mode gelap, taskbar, atau pintasan layar utama perangkat), area transparan tersebut memunculkan kotak persegi abu-abu/putih kusam (*gray box artifact*) di sekeliling lambang perisai.
  - **Pemrosesan Gambar (*Alpha Channel Normalization*):**
    - Memproses berkas grafis menggunakan PHP GD engine untuk menormalisasi nilai alfa: piksel latar belakang (`alpha >= 63`) diubah menjadi 100% transparan murni (`alpha: 127`), sedangkan piksel tepi perisai (*anti-aliasing boundary*) dipetakan secara halus dan proporsional dari rentang `0..63` ke `0..127`.
    - Menyinkronkan hasil gambar transparan ke seluruh lokasi berkas:
      - [`Logo/Logo1.png`](file:///c:/Proyek%20Gua/WEx/Logo/Logo1.png)
      - [`public/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/Logo1.png)
      - [`public/images/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/images/Logo1.png)
      - [`public/favicon.png`](file:///c:/Proyek%20Gua/WEx/public/favicon.png)
      - [`public/favicon.ico`](file:///c:/Proyek%20Gua/WEx/public/favicon.ico)
  - **Pemberian Parameter Cache-Busting:**
    - Menambahkan parameter dinamis `?v={{ filemtime(...) }}` pada tag favicon dan gambar di:
      - [`resources/views/layouts/admin.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/layouts/admin.blade.php)
      - [`resources/views/layouts/siswa.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/layouts/siswa.blade.php)
      - [`resources/views/auth/login.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/auth/login.blade.php)
      - [`resources/views/admin/rekap/cetak.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/rekap/cetak.blade.php)
    - Memastikan browser klien segera mengunduh berkas baru tanpa tertahan cache lawas.
  - **Verifikasi Pengujian:**
    - Menjalankan 24 automated tests dengan 134 assertions (100% pass).
- **Impact:**
---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Presisi Posisi & Centering Simetris Logo Sekolah (`Logo1.png`)
- **Technical Implementation:**
  - **Identifikasi Masalah (*Disproportionate Canvas Margin*):**
    - Berkas grafis asli memiliki perisai berukuran `122x149` piksel di dalam kanvas `200x200`.
    - Namun, perisai tersebut tidak berada tepat di titik tengah: jarak ke batas atas adalah 14 piksel, sedangkan jarak ke batas bawah adalah 37 piksel (terdapat pergeseran ke atas sebesar 23 piksel).
    - Akibatnya, saat dimuat dengan `object-contain` di dalam wadah persegi berbingkai kartu login atau sidebar, logo tampak condong ke atas dan tidak presisi di tengah (*off-center*).
  - **Koreksi Posisi (*Geometric & Optical Centering*):**
    - Menggunakan PHP GD engine untuk menggeser posisi perisai tepat ke titik tengah kanvas `200x200`:
      - Jarak batas atas (*top space*): 26 piksel.
      - Jarak batas bawah (*bottom space*): 25 piksel.
      - Jarak batas kiri (*left space*): 39 piksel.
      - Jarak batas kanan (*right space*): 39 piksel.
      - Titik pusat logo kini tepat berada di koordinat `X=99.5, Y=100` (toleransi simetris 0.5px).
    - Memperbarui seluruh aset logo di:
      - [`Logo/Logo1.png`](file:///c:/Proyek%20Gua/WEx/Logo/Logo1.png)
      - [`public/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/Logo1.png)
      - [`public/images/Logo1.png`](file:///c:/Proyek%20Gua/WEx/public/images/Logo1.png)
      - [`public/favicon.png`](file:///c:/Proyek%20Gua/WEx/public/favicon.png)
      - [`public/favicon.ico`](file:///c:/Proyek%20Gua/WEx/public/favicon.ico)
  - **Verifikasi Pengujian:**
    - Menjalankan 24 automated tests dengan 134 assertions (100% pass).
- **Impact:**
  - Logo sekolah kini tampil simetris dan seimbang (*dead-center*) secara horizontal maupun vertikal di dalam kartu login, sidebar, favicon, dan seluruh elemen antarmuka.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Fitur Impor Massal Akun Siswa via Spreadsheet / CSV (*Bulk Account Import*)
- **Technical Implementation:**
  - **Routing & Controller ([`app/Http/Controllers/SiswaKelolaController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/SiswaKelolaController.php) & [`routes/web.php`](file:///c:/Proyek%20Gua/WEx/routes/web.php)):**
    - `downloadTemplate()`: Mengunduh berkas template resmi `template_import_siswa.csv` dengan penyertaan UTF-8 BOM (`\xEF\xBB\xBF`) agar langsung kompatibel dibuka di Microsoft Excel Windows dan Google Sheets tanpa karakter rusak, lengkap dengan 3 baris data contoh yang realistis.
    - `importForm()`: Menampilkan antarmuka impor massal terpadu dilengkapi kartu panduan 9 kolom dan referensi nama Tempat PKL serta Guru Pembimbing aktif.
    - `importPreview()`: Membaca berkas spreadsheet, mendeteksi pemisah kolom otomatis (koma, titik-koma, tab), melakukan validasi mendalam (kelengkapan kolom, keunikan NISN/username/email baik antar-baris berkas maupun terhadap basis data sistem, auto-generate username dan password default, serta pencocokan tempat PKL dan guru pembimbing).
    - `importConfirm()`: Melakukan eksekusi pembuatan akun massal ke basis data di dalam `DB::transaction()` untuk menjamin integritas data (ACID compliant).
  - **Antarmuka Pengguna ([`resources/views/admin/siswa/import.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/siswa/import.blade.php) & [`resources/views/admin/siswa/index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/siswa/index.blade.php)):**
    - Menambahkan tombol *"Unduh Template"* dan *"Import Akun (Bulk)"* di tajuk halaman manajemen siswa.
    - Menyediakan area seret-dan-lepas (*drag & drop*) berkas dengan indikator nama dan ukuran berkas terpilih.
    - Menyediakan kartu metrik hasil pemeriksaan: Total Baris, Siap Diimpor (Valid), dan Gagal/Duplikat.
    - Menyediakan tabel pratinjau interaktif dengan badge status warna (`Siap` hijau vs `Gagal` merah) beserta detail kesalahan validasi secara transparan sebelum data benar-benar disimpan.
  - **Pengujian Otomatis ([`tests/Feature/PklMonitoringTest.php`](file:///c:/Proyek%20Gua/WEx/tests/Feature/PklMonitoringTest.php)):**
    - Menambahkan 5 pengujian otomatis baru:
      1. `test_admin_can_download_siswa_import_template`
      2. `test_admin_can_preview_valid_siswa_import_file`
      3. `test_admin_can_preview_siswa_import_with_validation_errors`
      4. `test_admin_can_confirm_bulk_import_and_accounts_are_created`
      5. `test_non_admin_cannot_access_siswa_bulk_import`
    - Seluruh **29 automated tests** (155 assertions) lulus 100% (*green*).
- **Impact:**
  - Administrator sekolah kini dapat mendaftarkan puluhan hingga ratusan siswa sekaligus hanya dalam hitungan detik tanpa perlu menginput formulir satu per satu.
  - Alur konfirmasi dua langkah (*preview & confirm*) mencegah terjadinya duplikasi data dan kesalahan input secara proaktif.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Perbaikan Format Template Excel (.xls SpreadsheetML) & Dual Parser (Excel XML / CSV Delimiter Auto-Detect)
- **Technical Implementation:**
  - **Identifikasi Masalah (*Root Cause*):**
    - Pada Microsoft Excel di sistem operasi Windows dengan pengaturan regional bahasa Indonesia, pemisah kolom standar (*list separator*) menggunakan titik koma (`;`), bukan koma (`,`). Berkas CSV berkoma menyebabkan Excel menggabungkan seluruh 9 kolom teks ke dalam Kolom A tunggal.
    - Berkas CSV polos tidak memiliki penetapan tipe data sel (*cell data typing*), sehingga berisiko menghilangkan angka nol di depan (*leading zero*) pada NISN (misalnya `0081234561` terbaca sebagai integer atau notasi ilmiah).
  - **Generator Template Resmi Excel SpreadsheetML (`SiswaKelolaController@downloadTemplate`):**
    - Format unduhan default kini menghasilkan berkas resmi **Microsoft Excel 2003 XML (`.xls` SpreadsheetML)** dengan MIME `application/vnd.ms-excel`.
    - Dilengkapi styling header profesional: latar belakang teal `#008294`, teks putih tebal (*bold*), perataan tengah, tinggi baris 26px, dan batas tepi (*border*).
    - Menetapkan lebar kolom proporsional (*custom column widths* antara 90px hingga 190px) untuk keterbacaan maksimal.
    - Mengunci format sel NISN sebagai **Text (`@`)** (`<NumberFormat ss:Format="@"/>`), menjamin angka nol di depan NISN tidak pernah hilang atau terpotong saat dibuka di Microsoft Excel.
    - Menyediakan opsi alternatif unduhan format **CSV** dengan pemisah titik koma (`;`) dan UTF-8 BOM untuk kompatibilitas ganda.
  - **Peningkatan Mesin Parser Impor (`SiswaKelolaController@importPreview`):**
    - Mengimplementasikan deteksi ganda otomatis (*Dual-Engine Auto-Detect*): jika berkas mengandung XML Spreadsheet 2003 (`urn:schemas-microsoft-com:office:spreadsheet` atau `<Workbook`), sistem menggunakan `SimpleXML` dengan XPath dan *Index-Aware cell mapping* (`ss:Index`) untuk menangani sel kosong secara akurat.
    - Jika berkas berformat teks/CSV, parser otomatis menganalisis baris pertama untuk mendeteksi pemisah koma (`,`), titik koma (`;`), atau tab (`\t`).
  - **Antarmuka Pengguna ([`import.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/siswa/import.blade.php) & [`index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/siswa/index.blade.php)):**
    - Menyediakan dua tombol unduh terpisah: tombol utama *"Unduh Template Excel (.xls)"* dan tombol sekunder *"Template CSV"*.
    - Memperbarui atribut unggahan formulir (`accept=".xls,.xlsx,.csv,.txt,.xml"`) beserta teks keterangan format berkas yang didukung.
  - **Pengujian Otomatis ([`PklMonitoringTest.php`](file:///c:/Proyek%20Gua/WEx/tests/Feature/PklMonitoringTest.php)):**
    - Menambahkan pengujian `test_admin_can_download_siswa_import_template` (Excel .xls).
    - Menambahkan pengujian `test_admin_can_download_siswa_import_template_csv` (CSV via `streamedContent`).
    - Menambahkan pengujian `test_admin_can_preview_valid_siswa_import_excel_file` (validasi end-to-end berkas XML Spreadsheet).
    - Seluruh **31 tests** dengan **165 assertions** lulus 100% (*green*).
- **Impact:**
  - Saat admin mengunduh template dan membukanya di Microsoft Excel, seluruh 9 kolom langsung terbagi rapi di kolom A sampai I dengan lebar kolom presisi dan header berwarna teal yang menarik.
  - NISN terlindungi dari konversi otomatis angka atau penghilangan angka nol awal.
  - Pengguna bebas mengunggah kembali berkas hasil editan baik dalam format Excel `.xls` maupun CSV tanpa kendala parsing.

---

### [DONE]
- **Status:** Selesai (Completed)
- **Feature:** Peningkatan Ekspor Spreadsheet Rekapitulasi Presensi & Jurnal PKL (Format Excel .xls & CSV Kompatibel)
- **Technical Implementation:**
  - **Penyediaan Mesin Generator SpreadsheetML Microsoft Excel ([`app/Http/Controllers/RekapController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/RekapController.php)):**
    - Mengembangkan metode `exportExcel(Request $request): Response` yang menghasilkan berkas spreadsheet XML murni Microsoft Excel (`application/vnd.ms-excel; charset=UTF-8`).
    - Merancang banner identitas resmi di baris atas dokumen (*Title* ukuran 14pt `#00626D`, *Subtitle*, rentang tanggal periode yang dipilih, dan waktu pencetakan).
    - Menerapkan header tabel berlatar belakang teal institusional `#008294`, teks putih tebal, perataan tengah, tinggi baris 28px, dan border kontinu.
    - Menetapkan lebar kolom yang proporsional (40px hingga 260px) untuk seluruh 12 kolom informasi (No, Tanggal, Jam Presensi, NISN, Nama Siswa, Kelas, Jurusan, Tempat PKL, Guru Pembimbing, Status Kehadiran, Rencana Tugas / Jurnal, Catatan Kendala).
    - Memformat sel NISN dengan gaya Text (`@`) agar angka nol di awal tidak terpotong saat dibuka di Microsoft Excel.
    - Memberikan pewarnaan semantik (*semantic badge styling*) pada status kehadiran: HADIR berlatar hijau lembut `#DEF7EC` dengan teks hijau gelap `#03543F`, IZIN/SAKIT berlatar kuning `#FEF08A`, dan ALFA berlatar merah `#FEE2E2`.
    - Mengaktifkan *wrap text* (`ss:WrapText="1"`) pada kolom Rencana Tugas dan Catatan untuk keterbacaan optimal.
    - Menambahkan baris ringkasan (*Summary Row*) di baris terbawah dengan kalkulasi total data presensi, total hadir, dan total izin/sakit.
  - **Perbaikan Format Delimiter Ekspor CSV ([`app/Http/Controllers/RekapController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/RekapController.php)):**
    - Memperbarui pemisah kolom dari koma (`,`) menjadi titik koma (`;`) pada `fputcsv` dan menyematkan UTF-8 BOM (`\xEF\xBB\xBF`).
    - Mencegah masalah pengelompokan teks satu baris penuh ke kolom A1 pada instalasi Microsoft Excel dengan pengaturan regional Indonesia.
  - **Refaktorisasi & DRY Filter Query ([`app/Http/Controllers/RekapController.php`](file:///c:/Proyek%20Gua/WEx/app/Http/Controllers/RekapController.php)):**
    - Mengekstraksi logika pembangun kueri filter tanggal, kelas, tempat PKL, dan status ke dalam metode privat `buildFilterQuery()` yang digunakan bersama oleh `index()`, `exportExcel()`, `exportCsv()`, dan `cetak()`.
  - **Pembaruan Rute & Antarmuka ([`routes/web.php`](file:///c:/Proyek%20Gua/WEx/routes/web.php) & [`resources/views/admin/rekap/index.blade.php`](file:///c:/Proyek%20Gua/WEx/resources/views/admin/rekap/index.blade.php)):**
    - Mendaftarkan rute `admin.rekap.export-excel` dengan proteksi middleware otentikasi peran `guru` dan `admin`.
    - Menyesuaikan tombol aksi di bagian header halaman rekapitulasi agar seirama dengan halaman template bulk akun siswa: tombol utama *"Ekspor Excel (.xls)"*, tombol sekunder *"Ekspor CSV"*, dan tombol *"Cetak Laporan"*.
  - **Pengujian Otomatis ([`tests/Feature/PklMonitoringTest.php`](file:///c:/Proyek%20Gua/WEx/tests/Feature/PklMonitoringTest.php)):**
    - Memperluas pengujian `test_admin_and_guru_can_access_rekap_and_export_csv` untuk memvalidasi endpoint `admin.rekap.export-excel` dan integritas pemisah titik koma pada `admin.rekap.export-csv`.
- **Impact:**
  - Laporan rekapitulasi kini dapat diunduh langsung dalam format Excel `.xls` dengan tabel berwarna, terstruktur, ber-border, dan langsung terbagi rapi pada kolom A sampai L tanpa teks menumpuk di kolom A1.
  - Berkas CSV alternatif juga membuka kolom secara terpisah dan otomatis pada sistem operasi/Excel berbahasa Indonesia.











