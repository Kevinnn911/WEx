# Product Specification (`product.md`) — Sistem Monitoring Siswa PKL

## 1. Ringkasan Produk
Sistem Monitoring Siswa PKL adalah aplikasi web terintegrasi yang membantu sekolah memantau kehadiran, aktivitas kerja, dan laporan harian siswa selama masa Praktik Kerja Lapangan (PKL) di dunia usaha/industri.

## 2. Alur Pengguna Utama
1. **Siswa (Mobile-First):**
   - Login Akun Siswa -> Cek Status Harian di Dashboard -> Absensi Wajib (Selfie Wajah + Tanda Tangan Digital) -> Isi Laporan / Rencana Tugas Harian -> Tinjau Riwayat Aktivitas & Profil.
2. **Guru Pembimbing & Admin (Desktop-First):**
   - Login Staff/Guru -> Dashboard Monitoring KPI (Total, Hadir, Belum Hadir, Sudah Laporan) -> Tabel Monitoring Lengkap (Filter Siswa, Kelas, Perusahaan) -> Detail Siswa (Review Foto Selfie, Tanda Tangan Digital, & Rencana Tugas Harian).

## 3. Fitur Fungsional
- **FR-S-01 (Login Siswa):** Autentikasi akun sekolah dengan username/email & password.
- **FR-S-02 (Dashboard Siswa):** Status real-time hari ini, sapaan dinamis, ringkasan tempat PKL & hari ke-n.
- **FR-S-03 (Absensi Hari Ini):** Presensi dengan batas 1x per hari.
- **FR-S-04 (Kamera Selfie):** Live stream webcam browser dengan capture foto & tombol ambil ulang.
- **FR-S-05 (Tanda Tangan Digital):** Canvas touch/mouse dengan tombol reset/hapus.
- **FR-S-06 (Laporan Harian):** Pengisian rencana tugas harian (hanya aktif setelah absen).
- **FR-S-07 (Riwayat Aktivitas):** Log rekap presensi dan laporan harian lampau.
- **FR-S-08 (Profil Siswa):** Data diri siswa, pembimbing, tempat PKL, dan logout.
- **FR-A-01 (Dashboard Monitoring):** Ringkasan KPI dan status kelengkapan aktivitas.
- **FR-A-02 (Tabel Monitoring):** Tabel pemantauan absensi dan laporan seluruh siswa.
- **FR-A-03 (Detail Siswa):** Peninjauan bukti foto, tanda tangan, dan tugas siswa.
