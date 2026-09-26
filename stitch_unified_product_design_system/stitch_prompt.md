# GOOGLE STITCH PROMPTS — Sistem Monitoring Siswa PKL

> Gunakan **satu prompt per generation/screen**. Semua screen memakai shared design system yang sama dari `DESIGN.md`.

---

# Shared Context — Berlaku untuk Semua Screen

Design a responsive **PKL Student Monitoring System** for Indonesian vocational-school students, teachers, and school administrators. The product helps schools monitor daily PKL attendance and student activity through face-photo attendance, digital signatures, daily task reports, monitoring dashboards, and activity history.

## Overall Style

Visual style: **friendly soft-card product UI** inspired by the provided mobile reference. Use playful pastel surfaces for the student experience and a slightly denser, more professional adaptation for teacher/admin screens. Mood: **friendly, trustworthy, calm, approachable**.

Key visual cues:

- Soft off-white canvas, not pure white.
- White cards with thin neutral borders and very subtle shadows.
- Brand teal/cyan as the main CTA and active-state color.
- Warm orange as secondary emphasis.
- Pastel cyan, mint, and soft yellow for feature/status surfaces.
- Rounded humanist/geometric typography such as **Nunito Sans**.
- Rounded cards, buttons, icon containers, and pill status badges.
- Friendly outline icons similar to Lucide.
- Avoid childish decorative overload; this is a real school productivity product.

## Shared Design Tokens

Colors:

- Primary: `#0B7C8A` — primary CTA, active nav, focus/selected states.
- Primary visual accent: `#20A7B8` — decorative teal only; do not use with small white text.
- Primary soft: `#BFEAF4`.
- Mint soft: `#CEF4D8`.
- Yellow soft: `#FFF0BD`.
- Accent orange: `#FF9B2F` with dark text `#142A33`.
- Background: `#F7FBFC`.
- Surface: `#FFFFFF`.
- Text primary: `#142A33`.
- Text secondary: `#5F6F76`.
- Border: `#E3ECEE`.
- Success: `#18794E`.
- Warning: `#A15C00`.
- Error: `#B42318`.

Typography:

- Font family: Nunito Sans or closest rounded humanist/geometric sans.
- Page title: 28–32px, 800, line-height 1.2.
- Section title: 20–24px, 700, line-height 1.2–1.25.
- Card title: 16–18px, 700.
- Body: 14–16px, 400–500, line-height 1.5.
- Label/caption: 12–14px, 600.
- KPI: 32–40px, 700–800, tabular figures.

Spacing:

- Use `4 / 8 / 16 / 24 / 32 / 48 / 64 / 96` only.
- Student mobile: 8px base rhythm.
- Dense admin controls may use a 4px sub-grid.

Radius:

- Inputs/buttons: 8–12px.
- Cards: 16px.
- Hero/feature cards: 20–24px.
- Pills/avatar: full radius.

Elevation:

- Flat/tonal first.
- Default card: `1px solid #E3ECEE` + `0 1px 2px rgba(20,42,51,0.05)`.
- Avoid heavy/glowing shadows.

Interactions:

- Hover desktop: subtle color/elevation shift, 150–200ms ease-out.
- Focus: visible 2px primary ring with 2px offset.
- Loading: skeleton screens.
- Error: inline alert near action/field.
- Success: toast for lightweight actions; clear success card after attendance/report submission.
- Disabled: clearly disabled but still readable.

Responsive:

- Breakpoints: `sm 640`, `md 768`, `lg 1024`, `xl 1280`, `2xl 1536`.
- Column convention: desktop 12, tablet 8, mobile 4.
- Student experience is mobile-first. Keep student content around 480–560px max width on large screens.
- School dashboard is desktop-first with content max-width around 1440px.
- Minimum touch target 44×44px.

Accessibility:

- WCAG AA minimum.
- Body contrast ≥4.5:1, large text ≥3:1, non-text UI ≥3:1.
- Never communicate status with color only; pair icon + label.
- Semantic HTML: header/nav/main/section/form/button/table.
- Full keyboard navigation for school dashboard.

Tech output:

Generate code as **Next.js + TypeScript + Tailwind CSS v4**. Use semantic HTML5 and modular React components. Tailwind v4 must use CSS-first configuration with `@theme`. Prefer shadcn/ui primitives where appropriate. Use Lucide-style outline icons.

Global rules to never break:

- Never expose Google Drive credentials or server secrets in frontend code.
- Never allow a student to access another student's data.
- Never allow more than one attendance submission per student per date.
- Never add GPS, geofencing, face recognition AI, check-out, chat, QR code, or other V2 features to this MVP.
- Never use random spacing outside the 4/8px rhythm.
- Never use pure `#000000` as main text or pure `#FFFFFF` as a large page canvas.
- Never use fixed height for text-heavy cards/list rows.
- Always use real Indonesian contextual content, not lorem ipsum.

---

# Prompt 01 — Login Siswa

[CONTEXT]
Design the **Student Login** screen for a PKL monitoring web app. Students use school-issued accounts to access their PKL attendance and daily report tools.

[SCREEN TO GENERATE]
Generate one mobile-first login screen.

Layout:

- Top: small friendly PKL Monitoring brand mark and product name.
- Main: centered welcome block with soft cyan illustration/icon area.
- Form card: username/email field, password field with show/hide action, primary login button.
- Bottom: short text explaining that accounts are provided by the school.

[COMPONENTS]

- Brand icon in rounded cyan container.
- Heading: `Masuk ke PKL Monitoring`.
- Supporting text: `Pantau absensi dan laporan kegiatan PKL kamu setiap hari.`
- Email/username input.
- Password input.
- Primary CTA: `Masuk`.
- Inline error example: `Email/username atau password tidak sesuai.`

[RESPONSIVE]

Mobile-first. On desktop center the form in a max-width 440px container; do not stretch it into a wide desktop form.

[ACCESSIBILITY]

Visible labels, keyboard friendly, focus rings, password button accessible name.

---

# Prompt 02 — Dashboard Siswa

[CONTEXT]
Design the **Student Dashboard**. It is the first screen after login and must let the student understand today's PKL status in a few seconds.

[SCREEN TO GENERATE]
Generate one mobile-first dashboard screen inspired closely by the reference UI's soft rounded card composition.

Layout:

- Top header: avatar, greeting `Selamat Pagi, Nathan`, small date indicator.
- Internship info card: company `PT Telkom Indonesia`, class `XI TKJ 1`, period summary.
- Hero daily-status card using soft cyan background.
- Two feature cards below: `Absensi` and `Laporan Harian`.
- Secondary card: `Riwayat Aktivitas`.
- Bottom fixed navigation: Home, Absensi, Laporan, Riwayat, Profil.

[COMPONENTS]

Hero status card:

- Heading: `Aktivitas PKL Hari Ini`.
- Date: `22 September 2026`.
- Status indicator: `Belum Absen`.
- Supporting text: `Lakukan absensi sebelum mengisi laporan harian.`
- Primary CTA: `Absensi Sekarang`.

Feature cards:

- Absensi: camera/signature icon, status pill.
- Laporan Harian: document icon, `Belum Dikirim`.
- Riwayat Aktivitas: compact recent activity preview.

Use friendly illustrations/icons, pastel cyan/mint/yellow feature surfaces, no unnecessary charts.

[INTERACTIONS]

- If attendance is submitted, change hero state to `Sudah Absen • 07:42 WIB` and primary CTA becomes `Buat Laporan`.
- If attendance + report are complete, hero becomes success state `Aktivitas Hari Ini Selesai`.

---

# Prompt 03 — Absensi Hari Ini

[CONTEXT]
Design the **Student Attendance** screen. Attendance requires a face photo and a digital signature, and can only be submitted once per day.

[SCREEN TO GENERATE]
Generate one mobile-first attendance screen.

Layout:

- Top app bar: back button, title `Absensi Hari Ini`, date.
- Step/status indicator with two required items: Foto Wajah and Tanda Tangan.
- Large camera card.
- Signature card.
- Date/time summary.
- Sticky bottom submit area.

[COMPONENTS]

Camera card:

- 4:3 camera preview container.
- Empty state icon if permission has not been granted.
- CTA `Buka Kamera`.
- Capture action `Ambil Foto`.
- Retake action `Ambil Ulang` after photo exists.

Signature card:

- White signature canvas with clear border.
- Label `Tanda Tangan Digital`.
- Helper `Tanda tangan di dalam area berikut.`
- Secondary button `Hapus`.

Summary:

- `22 September 2026`.
- Current time example `07:42 WIB`.

Primary CTA:

- `Kirim Absensi`.
- Disabled until face photo and signature are available.

[STATE]

Success state after submit:

- Large success icon.
- `Absensi Berhasil`.
- `Tercatat pada 07:42 WIB`.
- CTA `Lanjut Buat Laporan`.

Do not include GPS, map, face recognition scoring, geofence, or check-out controls.

---

# Prompt 04 — Laporan Harian

[CONTEXT]
Design the **Daily Report / Task Plan** screen used after attendance submission. The MVP stores a text task plan in Supabase.

[SCREEN TO GENERATE]
Generate one mobile-first report screen.

Layout:

- Top app bar: back, title `Laporan Harian`.
- Small completion context card showing attendance is already complete.
- Date row.
- Large text input card.
- Optional notes field if needed by the existing data model, visually secondary.
- Sticky submit CTA.

[CONTENT]

Heading: `Apa yang akan Anda kerjakan hari ini?`

Textarea sample:

`Hari ini saya akan melakukan konfigurasi jaringan dan maintenance komputer bagian administrasi.`

Helper:

`Tuliskan rencana pekerjaan secara singkat dan jelas.`

Primary CTA:

`Kirim Laporan`.

[STATE]

- Loading skeleton while fetching today's record.
- Validation for empty report.
- Success state: `Laporan Hari Ini Berhasil Dikirim`.
- After successful submit, do not show an editable duplicate-submission flow unless backend policy explicitly allows edits.

---

# Prompt 05 — Riwayat Aktivitas Siswa

[CONTEXT]
Design the **Student Activity History** screen showing attendance and report history.

[SCREEN TO GENERATE]
Generate one mobile-first history screen.

Layout:

- Top app bar title `Riwayat Aktivitas`.
- Month/date filter control.
- Vertical list of day cards.
- Each day card uses auto height and shows attendance + report state.
- Bottom navigation remains visible.

[SAMPLE DATA]

- 22 Sep 2026 — Hadir — 07:42 WIB — Laporan terkirim.
- 21 Sep 2026 — Hadir — 07:50 WIB — Laporan terkirim.
- 20 Sep 2026 — Libur — no attendance.
- 19 Sep 2026 — Hadir — 07:45 WIB — Laporan terkirim.
- 18 Sep 2026 — Hadir — 08:02 WIB — Laporan terkirim.

[COMPONENTS]

- Filter button/select.
- Status pills with icon + label.
- Expandable day card or `Lihat Detail` action to show report text.
- Empty state: `Belum ada riwayat pada periode ini.`

Avoid dense desktop-table styling on mobile.

---

# Prompt 06 — Profil Siswa

[CONTEXT]
Design the **Student Profile / PKL Information** screen.

[SCREEN TO GENERATE]
Generate one mobile-first profile screen inspired by the reference profile/settings screenshot.

Layout:

- Top centered avatar.
- Student name `Nathan Hall`.
- `XI TKJ 1 • Teknik Komputer dan Jaringan`.
- Main card groups with icon-leading rows.

[COMPONENTS]

Section `Informasi PKL`:

- Tempat PKL — `PT Telkom Indonesia`.
- Guru Pembimbing — `Bapak Andi Pratama`.
- Periode — `1 September 2026 – 30 November 2026`.

Section `Akun`:

- Email/username.
- Optional account information row.
- Logout row/button.

Do not invent parental controls or settings from the visual reference; adapt its layout only, not its product features.

---

# Prompt 07 — Login Guru / Admin

[CONTEXT]
Design the **School Staff Login** screen for teachers and administrators.

[SCREEN TO GENERATE]
Generate one responsive login screen.

Layout:

- Desktop: two-column layout inside a centered container.
- Left: product intro, short monitoring benefits, soft abstract/student activity illustration.
- Right: login card.
- Mobile: stack with form first after compact brand header.

[CONTENT]

Heading: `Masuk ke Dashboard PKL`.
Subheading: `Pantau kehadiran dan aktivitas siswa PKL dalam satu tempat.`
Fields: Email / Username, Password.
CTA: `Masuk`.

Keep the playful reference style restrained and professional for staff.

---

# Prompt 08 — Dashboard Monitoring Sekolah

[CONTEXT]
Design the **School Monitoring Dashboard** for teachers/admins. It summarizes daily PKL activity across students.

[SCREEN TO GENERATE]
Generate one desktop-first dashboard screen.

Layout:

- Left collapsible sidebar.
- Top bar with page title, date `22 September 2026`, search/quick profile area.
- Main KPI row.
- Main monitoring summary card.
- Recent/incomplete student activity list.
- Quick filters.

[SIDEBAR]

- Dashboard — active.
- Monitoring.
  - Absensi.
  - Laporan.
- Data PKL.
- Akun.
- Pengaturan.

[KPI CARDS]

Use sample numbers:

- `120` Total Siswa.
- `98` Sudah Absen.
- `22` Belum Absen.
- `91` Sudah Laporan.

Use tabular figures and small semantic status icon. Use pastel card accents without turning the screen into a children's dashboard.

[MONITORING SUMMARY]

Show three statuses:

- `Lengkap` — Absensi + laporan sudah dikirim.
- `Belum Lengkap` — Sudah absensi tetapi laporan belum dikirim.
- `Belum Aktivitas` — Belum melakukan absensi dan laporan.

Show a compact progress visualization or segmented summary only if it improves clarity. Keep data readable without decorative chart overload.

---

# Prompt 09 — Monitoring Absensi & Laporan

[CONTEXT]
Design the primary **Monitoring Table** used by teachers/admins to inspect student activity.

[SCREEN TO GENERATE]
Generate one desktop-first monitoring screen.

Layout:

- Sidebar + top bar shared with dashboard.
- Page heading `Monitoring PKL`.
- Filter/search toolbar.
- Main data table card.
- Pagination footer.

[FILTERS]

- Search by student name.
- Kelas.
- Jurusan.
- Tempat PKL.
- Guru pembimbing.
- Tanggal.
- Status absensi.
- Status laporan.

[TABLE COLUMNS]

- Nama.
- Kelas/Jurusan.
- Tempat PKL.
- Guru Pembimbing.
- Absensi.
- Jam.
- Laporan.
- Aktivitas.
- Action.

[SAMPLE ROWS]

- Nathan Hall — XI TKJ 1 — PT Telkom — 07:42 — Absensi ✓ — Laporan ✓ — `Lengkap`.
- Andi Pratama — XI TKJ 1 — CV Teknologi — 08:01 — Absensi ✓ — Laporan ✓ — `Lengkap`.
- Budi Santoso — XI TKJ 2 — PT Network — no time — Absensi ✕ — Laporan ✕ — `Belum Aktivitas`.
- Dimas Putra — XI TKJ 2 — PT Digital — 07:39 — Absensi ✓ — Laporan ✕ — `Belum Lengkap`.

[BEHAVIOR]

- Sticky table header.
- Row hover.
- Action `Lihat Detail`.
- Loading skeleton rows.
- Empty filtered state.
- On mobile transform important row information into stacked cards; do not shrink the full desktop table to unreadable widths.

---

# Prompt 10 — Detail Siswa

[CONTEXT]
Design the **Student Detail** screen opened by a teacher/admin from the monitoring table.

[SCREEN TO GENERATE]
Generate one responsive detail screen.

Layout:

- Sidebar/topbar shared with school dashboard.
- Breadcrumb: `Monitoring / Nathan Hall`.
- Student summary header.
- 2-column desktop main content.
- Left: today's attendance and report.
- Right: student/PKL information.
- Bottom: activity history table/list.

[STUDENT INFO]

- Nathan Hall.
- XI TKJ 1.
- Teknik Komputer dan Jaringan.
- PT Telkom Indonesia.
- Period: `1 September 2026 – 30 November 2026`.

[TODAY ATTENDANCE]

- Status: `Hadir`.
- Date: `22 September 2026`.
- Time: `07:42 WIB`.
- Face photo preview card.
- Digital signature preview card.

[REPORT]

`Hari ini saya melakukan konfigurasi access point dan maintenance komputer bagian administrasi.`

[HISTORY]

- 22 Sep — Hadir — 07:42 — report complete.
- 21 Sep — Hadir — 07:50 — report complete.
- 20 Sep — Libur.

Never expose raw Google Drive credentials or technical storage IDs in the user-facing UI.

---

# Prompt 11 — Data PKL

[CONTEXT]
Design the **PKL Data Management** screen for administrators.

[SCREEN TO GENERATE]
Generate one desktop-first admin data screen with a tabbed section.

Layout:

- Sidebar shared with school dashboard.
- Heading `Data PKL`.
- Tabs: `Siswa`, `Guru`, `Tempat PKL`, `Penempatan PKL`.
- Search/filter toolbar.
- Main table.
- Primary action button based on active tab.

[ACTIVE TAB EXAMPLE]

Use `Penempatan PKL` as the initially active tab.

Columns:

- Nama Siswa.
- Kelas.
- Tempat PKL.
- Guru Pembimbing.
- Tanggal Mulai.
- Tanggal Selesai.
- Status.
- Action.

Primary CTA: `Tambah Penempatan`.

[CRUD INTERACTIONS]

- Create/edit uses a side sheet or modal.
- Delete uses confirmation dialog.
- Success uses toast.
- Forms use labeled inputs/selects and visible validation.

Keep this screen denser than student UI but use the same brand tokens.

---

# Prompt 12 — Manajemen Akun

[CONTEXT]
Design the **Account Management** screen for school administrators.

[SCREEN TO GENERATE]
Generate one desktop-first account management screen.

Layout:

- Sidebar.
- Heading `Manajemen Akun`.
- Role tabs: `Siswa`, `Guru`, `Admin`.
- Search + status filter.
- Main account table.
- Primary CTA `Tambah Akun`.

[TABLE]

Columns:

- Nama.
- Email / Username.
- Role.
- Status.
- Last/created info if available from backend.
- Action.

[INTERACTION]

Use create/edit modal or side sheet. Do not invent password-reset/email flows beyond what the implemented auth system supports; keep unsupported actions visually absent or clearly disabled in prototype.

---

# Prompt 13 — Pengaturan Periode PKL

[CONTEXT]
Design the **PKL Settings / Period Management** screen for admins.

[SCREEN TO GENERATE]
Generate one responsive settings screen.

Layout:

- Sidebar.
- Heading `Pengaturan PKL`.
- Card `Periode PKL`.
- List/table of active and previous periods.
- Form/dialog for creating or editing a period.

[CONTENT]

Example active period:

- `PKL Semester Ganjil 2026`.
- Start: `1 September 2026`.
- End: `30 November 2026`.
- Status: `Aktif`.

Use clear destructive-action confirmation and success feedback.

---

# Implementation Notes for Stitch Output

When generating code for any screen:

1. Use **Next.js + TypeScript + Tailwind CSS v4**.
2. Use CSS-first Tailwind v4 `@theme` tokens.
3. Build reusable components, for example:
   - `AppHeader`
   - `BottomNav`
   - `SchoolSidebar`
   - `StatusBadge`
   - `MetricCard`
   - `StudentSummaryCard`
   - `AttendanceCard`
   - `ReportCard`
   - `DataTable`
   - `FilterBar`
   - `EmptyState`
   - `SkeletonCard`
4. Use semantic HTML.
5. Preserve auto-height for content-variable cards and table rows.
6. Make focus states visible.
7. Keep form labels explicit.
8. Use contextual Indonesian content.
9. Do not generate V2 features.
10. Preserve the reference style's rounded, pastel, friendly visual identity while making admin screens appropriately dense and professional.
