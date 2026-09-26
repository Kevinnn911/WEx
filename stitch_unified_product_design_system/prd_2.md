# PRD — Sistem Monitoring Siswa PKL

**Versi:** MVP v1  
**Platform:** Web App responsive  
**Frontend target:** Blade Components + Tailwind CSS + Alpine.js / Vanilla JS (Stitch Design System)  
**Backend:** Laravel 11 (PHP 8.3+)  
**Auth & Database:** Laravel Auth + SQLite / MySQL via Eloquent ORM  
**File storage:** Local Public Storage / Google Drive API  
**Deployment:** VPS / Laragon / Local Server

---

## 1. Ringkasan Produk

Sistem Monitoring Siswa PKL adalah aplikasi web yang membantu sekolah memantau kehadiran, aktivitas harian, dan laporan siswa selama Praktik Kerja Lapangan.

Setiap siswa memiliki akun masing-masing dan wajib melakukan aktivitas harian berupa:

1. Foto wajah saat absensi.
2. Tanda tangan digital.
3. Timestamp absensi.
4. Laporan/rencana tugas yang akan dikerjakan pada hari tersebut.

Guru/pembimbing sekolah dapat memantau siswa bimbingannya, sedangkan admin sekolah dapat mengelola keseluruhan data PKL dan melihat seluruh aktivitas.

Supabase digunakan untuk autentikasi, data master, metadata absensi, dan laporan berbentuk teks. Google Drive digunakan khusus untuk file foto wajah dan tanda tangan digital.

---

## 2. Masalah yang Diselesaikan

Monitoring PKL sering tersebar di banyak media dan sulit diperiksa secara konsisten. Sekolah membutuhkan satu sistem yang menjawab pertanyaan utama:

> Apakah sekolah dapat memonitor kehadiran dan aktivitas siswa PKL setiap hari dengan mudah?

MVP memprioritaskan alur:

**Absensi → Laporan → Monitoring → Riwayat → Administrasi**

---

## 3. Tujuan MVP

MVP harus memungkinkan sekolah untuk:

- Mengetahui siswa yang sudah atau belum melakukan absensi.
- Melihat foto wajah saat absensi.
- Melihat tanda tangan digital siswa.
- Melihat waktu absensi.
- Melihat laporan/rencana tugas harian siswa.
- Melihat riwayat absensi dan laporan.
- Memantau seluruh siswa PKL melalui satu dashboard.
- Mengelola data siswa, guru, tempat PKL, penempatan, akun, dan periode PKL.

---

## 4. Target Pengguna dan Hak Akses

### 4.1 Siswa

Siswa yang sedang menjalani PKL.

**Hak akses:**

- Login.
- Melihat informasi PKL miliknya.
- Melakukan absensi satu kali per hari.
- Mengambil foto wajah melalui kamera browser.
- Membuat tanda tangan digital.
- Mengirim laporan/rencana tugas harian.
- Melihat riwayat absensi sendiri.
- Melihat riwayat laporan sendiri.
- Tidak dapat mengakses data siswa lain.

### 4.2 Guru / Pembimbing Sekolah

Guru yang bertugas memantau siswa PKL.

**Hak akses:**

- Login.
- Melihat siswa bimbingan.
- Melihat status absensi siswa bimbingan.
- Melihat laporan harian siswa bimbingan.
- Melihat foto absensi.
- Melihat tanda tangan siswa.
- Melihat riwayat aktivitas siswa.

### 4.3 Admin Sekolah

Admin bertugas mengelola keseluruhan sistem.

**Hak akses:**

- Login.
- Melihat seluruh siswa, absensi, dan laporan.
- Mengelola akun siswa, guru, dan admin.
- Mengelola data siswa dan guru.
- Mengelola tempat PKL.
- Mengatur penempatan siswa PKL.
- Mengelola periode PKL.

---

## 5. Information Architecture

### 5.1 Navigasi Siswa — Mobile-first

Bottom navigation utama:

1. **Home**
2. **Absensi**
3. **Laporan**
4. **Riwayat**
5. **Profil**

### 5.2 Navigasi Guru/Admin — Desktop-first

Sidebar utama:

- Dashboard
- Monitoring
  - Absensi
  - Laporan
- Data PKL
  - Siswa
  - Guru
  - Tempat PKL
  - Penempatan PKL
- Akun
  - Siswa
  - Guru
  - Admin
- Pengaturan

Pada tablet, sidebar dapat collapse menjadi icon rail. Pada mobile, navigasi sekolah berubah menjadi drawer/menu sheet.

---

## 6. Daftar Screen MVP

### Siswa

1. Login Siswa
2. Dashboard Siswa
3. Absensi Hari Ini
4. Laporan Harian
5. Riwayat Aktivitas
6. Profil / Informasi PKL

### Guru / Admin

7. Login Sekolah
8. Dashboard Monitoring
9. Monitoring Absensi & Laporan
10. Detail Siswa
11. Data PKL
12. Manajemen Akun
13. Pengaturan / Periode PKL

---

## 7. User Flow Utama

### 7.1 Flow Siswa Harian

```text
Login
  ↓
Dashboard Siswa
  ↓
Cek Status Hari Ini
  ↓
Absensi
  ├── Ambil Foto Wajah
  ├── Tanda Tangan Digital
  └── Kirim Absensi
  ↓
Laporan Harian
  ↓
Kirim Rencana Tugas
  ↓
Status Hari Ini = Selesai
```

### 7.2 Flow Guru

```text
Login
  ↓
Dashboard Monitoring
  ↓
Lihat Statistik Hari Ini
  ↓
Filter / Cari Siswa
  ↓
Buka Detail Siswa
  ↓
Lihat Absensi + Foto + Tanda Tangan + Laporan + Riwayat
```

### 7.3 Flow Admin

```text
Login
  ↓
Dashboard
  ↓
Data PKL / Akun
  ↓
Kelola Siswa, Guru, Perusahaan, Penempatan, Periode
```

---

## 8. Functional Requirements — Siswa

### FR-S-01 — Login

- Siswa login menggunakan akun yang dibuat sekolah.
- Sistem memvalidasi credential melalui Supabase Auth.
- Setelah login berhasil, siswa diarahkan ke Dashboard Siswa.
- Error login harus muncul sebagai inline alert yang jelas.

### FR-S-02 — Dashboard Siswa

Dashboard menampilkan:

- Sapaan berdasarkan nama siswa.
- Nama tempat PKL.
- Tanggal hari ini.
- Status absensi hari ini.
- Jam absensi jika sudah hadir.
- Status laporan hari ini.
- CTA **Absensi Sekarang** jika belum absen.
- CTA **Buat Laporan** setelah absensi berhasil.
- Ringkasan progres/aktivitas harian.

Contoh konten:

```text
Selamat Pagi, Nathan
PT Telkom Indonesia
22 September 2026

Absensi: Belum Absen
Laporan Harian: Belum Dikirim
```

### FR-S-03 — Absensi

Absensi wajib memiliki:

- Foto wajah.
- Tanda tangan digital.
- Tanggal.
- Jam.
- Identitas siswa.

Aturan:

- Siswa hanya dapat melakukan absensi satu kali dalam satu hari.
- Tombol submit disabled sampai foto dan tanda tangan tersedia.
- Setelah berhasil, UI menampilkan success state dan timestamp.

### FR-S-04 — Kamera

- Browser meminta permission kamera.
- Siswa dapat membuka preview kamera.
- Siswa dapat mengambil foto.
- Siswa dapat mengambil ulang foto sebelum submit.
- File foto dikirim ke backend, bukan langsung ke Google Drive API menggunakan credential rahasia.

### FR-S-05 — Tanda Tangan Digital

- Gunakan signature pad.
- Siswa dapat menggambar tanda tangan.
- Tersedia tombol **Hapus** / reset.
- Signature disimpan sebagai PNG.
- File dikirim ke backend untuk di-upload ke Google Drive.

### FR-S-06 — Laporan Harian

- Hanya dapat diisi setelah absensi hari tersebut berhasil.
- Menampilkan tanggal otomatis.
- Field utama: **Apa yang akan Anda kerjakan hari ini?**
- Laporan disimpan sebagai teks di Supabase.
- Setelah submit, status berubah menjadi selesai/lengkap.

### FR-S-07 — Riwayat

Siswa dapat melihat riwayat:

- Tanggal.
- Status kehadiran.
- Jam.
- Status laporan.
- Isi laporan per tanggal pada detail/expand state.

### FR-S-08 — Profil

Menampilkan minimal:

- Nama siswa.
- Kelas.
- Jurusan.
- Tempat PKL.
- Guru pembimbing.
- Periode PKL.
- Tombol logout.

---

## 9. Functional Requirements — Guru/Admin

### FR-A-01 — Dashboard Monitoring

Dashboard menampilkan statistik hari ini:

- Total siswa.
- Sudah absen.
- Belum absen.
- Sudah laporan.
- Belum lengkap.
- Belum aktivitas.

Gunakan KPI cards dengan tabular figures.

### FR-A-02 — Tabel Monitoring

Kolom utama:

- Nama siswa.
- Kelas / jurusan.
- Tempat PKL.
- Guru pembimbing.
- Status absensi.
- Jam absensi.
- Status laporan.
- Status aktivitas.
- Action untuk membuka detail siswa.

Filter:

- Nama siswa.
- Kelas.
- Jurusan.
- Tempat PKL.
- Guru pembimbing.
- Tanggal.
- Status absensi.
- Status laporan.

### FR-A-03 — Status Aktivitas

Gunakan tiga semantic status:

- **Lengkap** — absensi + laporan sudah dikirim.
- **Belum Lengkap** — sudah absensi tetapi laporan belum dikirim.
- **Belum Aktivitas** — belum absensi dan belum laporan.

Status tidak boleh hanya dibedakan dengan warna; selalu tampilkan icon + label.

### FR-A-04 — Detail Siswa

Detail menampilkan:

- Nama.
- Kelas.
- Jurusan.
- Tempat PKL.
- Periode PKL.
- Status absensi hari ini.
- Tanggal dan jam absensi.
- Foto wajah.
- Tanda tangan digital.
- Laporan hari ini.
- Riwayat aktivitas.

### FR-A-05 — Data PKL

Admin dapat mengelola:

- Data siswa.
- Data guru.
- Tempat PKL.
- Penempatan PKL.
- Periode PKL.

CRUD mengikuti endpoint/backend yang tersedia dan harus memiliki confirmation dialog untuk destructive action.

### FR-A-06 — Manajemen Akun

Admin dapat mengelola akun:

- Siswa.
- Guru.
- Admin.

UI harus menyediakan search, status akun, role, dan action menu.

---

## 10. Status dan State Sistem

### 10.1 Status Absensi

```text
NOT_SUBMITTED
PRESENT
```

### 10.2 Status Aktivitas Harian

```text
BELUM ABSEN
   ↓
SUDAH ABSEN
   ↓
BELUM LAPORAN
   ↓
SELESAI
```

### 10.3 UI States Wajib

Setiap screen data harus mempertimbangkan:

- Default state.
- Loading state menggunakan skeleton, bukan spinner sebagai satu-satunya feedback.
- Empty state.
- Error state.
- Success state.
- Disabled state.
- Focus state.
- Confirmation dialog untuk action destructive.

---

## 11. UI/UX Direction

### 11.1 Design Language

Arah visual mengadaptasi screenshot referensi:

- **Playful educational / friendly mobile UI** untuk area siswa.
- **Soft card-based interface** dengan pastel surfaces.
- **Rounded geometric/humanist sans typography**.
- **Flat / tonal elevation** dengan border halus dan shadow sangat ringan.
- **Teal/cyan** sebagai brand color.
- **Orange** sebagai warm accent untuk action/status penting.
- Pastel cyan, mint, dan yellow sebagai supporting surfaces.

Area sekolah mempertahankan design language yang sama tetapi dibuat lebih produktif dan lebih dense agar tabel dan monitoring mudah dipindai.

### 11.2 Mood

- Friendly
- Trustworthy
- Calm
- Approachable

### 11.3 Design Principles

- Informasi terpenting harus terbaca dalam 3–5 detik.
- Satu primary CTA dominan per screen.
- Gunakan real contextual copy, bukan lorem ipsum.
- Jangan bergantung pada warna saja untuk status.
- Hindari visual yang terlalu childish pada dashboard sekolah.

---

## 12. Design Tokens

### 12.1 Primitive Palette

```text
Teal 700       #0B7C8A
Teal 500       #20A7B8
Cyan 100       #BFEAF4
Mint 100       #CEF4D8
Yellow 100     #FFF0BD
Orange 500     #FF9B2F
Orange 700     #B85C00
Neutral 950    #142A33
Neutral 600    #5F6F76
Neutral 200    #E3ECEE
Neutral 050    #F7FBFC
Surface White  #FFFFFF
Success 700    #18794E
Warning 700    #A15C00
Error 700      #B42318
```

### 12.2 Semantic Tokens

```text
background            #F7FBFC
surface               #FFFFFF
text-primary          #142A33
text-secondary        #5F6F76
border                #E3ECEE
primary               #0B7C8A
on-primary            #FFFFFF
primary-soft          #BFEAF4
accent                #FF9B2F
on-accent             #142A33
success               #18794E
warning               #A15C00
error                 #B42318
```

`#0B7C8A` dipilih untuk primary CTA dengan white text agar memenuhi WCAG AA untuk body text. Orange terang dipakai dengan dark text, bukan white text.

### 12.3 Typography

Target family: **Nunito Sans** atau rounded humanist/geometric sans setara.

```text
Display / Page title : 28–32px, 700–800, line-height 1.2
Section title        : 20–24px, 700, line-height 1.2
Card title           : 16–18px, 700, line-height 1.25
Body                 : 14–16px, 400–500, line-height 1.5
Label                : 12–14px, 600, line-height 1.4
Caption              : 12px, 500, line-height 1.4
KPI                   : 32–40px, 700–800, tabular figures
```

### 12.4 Spacing

Gunakan token scale:

```text
4 / 8 / 16 / 24 / 32 / 48 / 64 / 96 px
```

- Mobile/student UI: base 8px.
- Dense table/admin UI: boleh memakai 4px sub-grid, tetapi tetap snap ke token scale.
- Card padding: 16–24px.
- Card gap: 12–16px, snap ke 4px grid.
- Section gap: 24–48px.

### 12.5 Radius

```text
sm    8px
md   12px
lg   16px
xl   24px
full 9999px
```

Gunakan radius secara proporsional; jangan satu radius global untuk semua komponen.

### 12.6 Elevation

Default:

```css
border: 1px solid #E3ECEE;
box-shadow: 0 1px 2px rgba(20, 42, 51, 0.05);
```

Gunakan tonal surfaces sebelum menambah shadow berat.

---

## 13. Component Requirements

### Student UI

- App header / greeting header.
- Internship info card.
- Daily status card.
- Primary CTA button.
- Secondary action card.
- Camera preview.
- Capture button.
- Signature pad.
- Textarea laporan.
- Status badges.
- Timeline/history list.
- Bottom navigation.
- Confirmation / success dialog.

### School UI

- Desktop sidebar.
- Topbar.
- KPI cards.
- Search input.
- Filter chips/selects.
- Data table.
- Status badges.
- Student summary card.
- Media preview card untuk foto/signature.
- Tabs.
- Pagination.
- Modal/dialog.
- CRUD form.
- Toast feedback.

---

## 14. Responsive Rules

Breakpoints:

```text
sm  640px
md  768px
lg  1024px
xl  1280px
2xl 1536px
```

Grid convention:

- Desktop: 12 columns.
- Tablet: 8 columns.
- Mobile: 4 columns.

### Student Experience

Mobile-first. Pada desktop, konten siswa tetap dibatasi sekitar 480–560px agar tidak menjadi terlalu lebar.

### School Experience

Desktop-first dengan max content width sekitar 1440px. Pada tablet, sidebar collapse. Pada mobile, table berubah menjadi card/list atau menyediakan horizontal overflow terkontrol bila kolom kritis tidak dapat disederhanakan.

Minimum touch target: **44×44px**.

---

## 15. Accessibility

- WCAG AA minimum.
- Body text contrast minimal 4.5:1.
- Large text minimal 3:1.
- Non-text UI component/focus indicator minimal 3:1.
- Visible focus ring 2px dengan offset 2px.
- Semantic HTML: `header`, `nav`, `main`, `section`, `form`, `button`, `table`.
- Input selalu memiliki label.
- Icon-only button memiliki accessible name.
- Status selalu memiliki text label, tidak hanya warna.
- Keyboard navigation harus usable untuk dashboard sekolah.
- Signature pad menyediakan instruksi dan reset action yang jelas.

---

## 16. Technical Architecture

```text
Siswa / Guru / Admin
        ↓
Next.js Web App
        ↓
Next.js API Routes / Supabase Edge Functions
      ↙     ↘
Supabase    Google Drive
Auth/DB     Foto + Signature
```

### Frontend

- Next.js
- TypeScript
- Tailwind CSS v4
- shadcn/ui

### Backend

- Next.js API Routes atau Supabase Edge Functions

### Authentication

- Supabase Auth

### Database

- Supabase PostgreSQL

### Security

- Supabase Row Level Security

### Storage

- Google Drive API untuk foto wajah dan tanda tangan.

---

## 17. Data Model Ringkas

### profiles

```text
id
user/auth reference
name
email
role
avatar_url
created_at
updated_at
```

Role:

```text
student
teacher
admin
```

### students

```text
id
profile_id
nis
nisn
class
major
phone
status
created_at
updated_at
```

### teachers

```text
id
profile_id
nip
phone
status
created_at
updated_at
```

### companies

```text
id
name
address
phone
supervisor_name
supervisor_phone
created_at
updated_at
```

### internships

```text
id
student_id
company_id
teacher_id
start_date
end_date
status
created_at
updated_at
```

### attendance

```text
id
student_id
internship_id
attendance_date
check_in_time
face_drive_file_id
signature_drive_file_id
status
created_at
```

### daily_reports

```text
id
student_id
internship_id
report_date
task_plan
notes
created_at
updated_at
```

---

## 18. API Surface MVP

### Authentication

```text
POST /api/auth/login
POST /api/auth/logout
```

### Attendance

```text
GET  /api/attendance/today
POST /api/attendance
GET  /api/attendance/history
```

### Reports

```text
GET  /api/reports/today
POST /api/reports
GET  /api/reports/history
```

### Admin

```text
GET /api/admin/dashboard
GET /api/admin/students
GET /api/admin/attendance
GET /api/admin/reports
```

### PKL

```text
GET    /api/internships
POST   /api/internships
PATCH  /api/internships/:id
DELETE /api/internships/:id
```

---

## 19. Google Drive Storage Rules

Google Drive hanya menyimpan:

- Foto wajah.
- Tanda tangan digital.

Supabase hanya menyimpan Google Drive File ID pada record absensi.

Contoh struktur folder:

```text
PKL-MONITORING/
└── 2026/
    └── Nathan-Hall/
        └── 2026-09/
            └── 2026-09-22/
                ├── face.jpg
                └── signature.png
```

Credential Google Drive wajib berada pada server environment variable. Frontend tidak boleh menerima secret Google Drive.

```text
Browser → Backend → Google Drive
```

---

## 20. Security Requirements

- Terapkan Supabase Row Level Security.
- Student hanya membaca/mengubah data miliknya sendiri sesuai flow MVP.
- Teacher hanya dapat melihat siswa yang menjadi bimbingannya.
- Admin dapat mengakses seluruh data sesuai fungsi administrasi.
- Jangan expose `GOOGLE_PRIVATE_KEY`, service credential, atau token server ke browser.
- Validasi satu absensi per siswa per tanggal pada server/database, bukan hanya di UI.
- Validasi role pada endpoint server.

---

## 21. Empty, Error, Loading, and Success Behavior

### Empty

Contoh:

- Tidak ada riwayat absensi.
- Tidak ada siswa pada filter.
- Belum ada laporan hari ini.

Gunakan empty state dengan icon/illustration kecil, penjelasan singkat, dan CTA bila relevan.

### Loading

- Skeleton untuk cards, table rows, dan detail panel.
- Hindari full-screen spinner kecuali initial authentication/bootstrap.

### Error

- Inline error dekat field/action terkait.
- Retry action untuk network/data fetch error.

### Success

- Success toast untuk CRUD ringan.
- Success state/card yang jelas setelah submit absensi dan laporan.

---

## 22. Non-Goals / Out of Scope MVP

Tidak masuk MVP v1:

- Face Recognition AI.
- GPS tracking.
- Geofencing.
- Check-out.
- Push notification.
- WhatsApp notification.
- Chat siswa dan guru.
- QR Code.
- Approval pembimbing perusahaan.
- Penilaian PKL.
- Export Excel lanjutan.
- AI analisis laporan.
- AI deteksi kecurangan.
- Dashboard perusahaan.

Jangan menambahkan fitur tersebut ke desain MVP sebagai primary feature.

---

## 23. Acceptance Criteria MVP

MVP dianggap berhasil apabila:

1. Siswa dapat login.
2. Siswa dapat mengambil foto wajah.
3. Siswa dapat memberikan tanda tangan digital.
4. Absensi tersimpan dengan benar.
5. Foto dan tanda tangan tersimpan di Google Drive.
6. Metadata absensi tersimpan di Supabase.
7. Siswa dapat membuat laporan harian.
8. Guru dapat melihat laporan siswa bimbingan.
9. Guru dapat melihat absensi siswa bimbingan.
10. Admin dapat melihat seluruh siswa.
11. Riwayat aktivitas siswa dapat ditampilkan.
12. Siswa tidak dapat mengakses data siswa lain.
13. UI responsif pada mobile, tablet, dan desktop sesuai role.
14. Interaksi utama dapat digunakan dengan keyboard dan memenuhi WCAG AA minimum.

---

## 24. Rules to Never Break

- Jangan menaruh Google Drive credential di frontend.
- Jangan mengizinkan absensi kedua pada tanggal yang sama.
- Jangan memberi siswa akses ke data siswa lain.
- Jangan membuat laporan harian sebagai file; laporan teks tetap disimpan di Supabase.
- Jangan menyimpan binary foto/signature langsung sebagai field database MVP jika arsitektur Google Drive masih digunakan.
- Jangan menggunakan warna saja untuk menyatakan status.
- Jangan memakai pure black `#000000` atau pure white `#FFFFFF` sebagai canvas/text utama area luas; gunakan soft neutral.
- Jangan memakai spacing acak di luar 4/8px grid.
- Jangan membuat fixed height untuk card/list yang kontennya dapat bertambah.
- Selalu pertahankan satu primary CTA yang jelas per screen.

