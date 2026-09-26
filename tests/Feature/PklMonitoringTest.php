<?php
// File: tests/Feature/PklMonitoringTest.php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\LaporanHarian;
use App\Models\PeriodePkl;
use App\Models\TempatPkl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PklMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private User $siswa;
    private User $guru;
    private User $admin;
    private TempatPkl $tempatPkl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempatPkl = TempatPkl::create([
            'nama_perusahaan' => 'PT Telkom Indonesia',
            'bidang' => 'Telekomunikasi & Jaringan',
            'alamat' => 'Jl. Japati No. 1, Bandung',
            'kota' => 'Bandung',
            'kontak' => '022-4521510',
        ]);

        PeriodePkl::create([
            'nama_periode' => 'PKL Semester Ganjil 2026',
            'tanggal_mulai' => '2026-09-01',
            'tanggal_selesai' => '2026-11-30',
            'is_aktif' => true,
        ]);

        $this->guru = User::create([
            'name' => 'Bapak Andi Pratama',
            'username' => 'guru',
            'email' => 'guru@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'guru',
            'nip' => '198503152010011002',
        ]);

        $this->admin = User::create([
            'name' => 'Administrator PKL',
            'username' => 'admin',
            'email' => 'admin@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->siswa = User::create([
            'name' => 'Nathan Hall',
            'username' => 'nathan',
            'email' => 'nathan@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'nisn' => '0071234567',
            'kelas' => 'XI TKJ 1',
            'jurusan' => 'Teknik Komputer dan Jaringan',
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
        ]);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sistem Monitoring PKL');
    }

    public function test_siswa_can_login_and_redirects_to_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => 'nathan',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));
        $this->assertAuthenticatedAs($this->siswa);
    }

    public function test_guru_can_login_and_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => 'guru',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->guru);
    }

    public function test_siswa_dashboard_displays_student_info(): void
    {
        $response = $this->actingAs($this->siswa)->get('/siswa/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Nathan Hall');
        $response->assertSee('PT Telkom Indonesia');
        $response->assertSee('XI TKJ 1');
    }

    public function test_siswa_can_submit_attendance_with_photo_and_signature(): void
    {
        $dummyBase64Png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        $response = $this->actingAs($this->siswa)->post('/siswa/absensi', [
            'foto_wajah' => $dummyBase64Png,
            'tanda_tangan' => $dummyBase64Png,
            'status' => 'hadir',
        ]);

        $response->assertRedirect(route('siswa.laporan'));

        $absensi = Absensi::where('siswa_id', $this->siswa->id)->first();
        $this->assertNotNull($absensi);
        $this->assertEquals(Carbon::today()->toDateString(), $absensi->tanggal->toDateString());
        $this->assertEquals('hadir', $absensi->status);
    }

    public function test_siswa_cannot_submit_duplicate_attendance_same_day(): void
    {
        $dummyBase64Png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        // Absensi pertama
        $this->actingAs($this->siswa)->post('/siswa/absensi', [
            'foto_wajah' => $dummyBase64Png,
            'tanda_tangan' => $dummyBase64Png,
            'status' => 'hadir',
        ]);

        // Absensi kedua pada hari yang sama ditolak
        $response = $this->actingAs($this->siswa)->post('/siswa/absensi', [
            'foto_wajah' => $dummyBase64Png,
            'tanda_tangan' => $dummyBase64Png,
            'status' => 'hadir',
        ]);

        $response->assertRedirect(route('siswa.absensi'));
        $this->assertEquals(1, Absensi::where('siswa_id', $this->siswa->id)->count());
    }

    public function test_siswa_cannot_submit_laporan_before_absensi(): void
    {
        $response = $this->actingAs($this->siswa)->get('/siswa/laporan');
        $response->assertRedirect(route('siswa.absensi'));
    }

    public function test_siswa_can_submit_laporan_after_absensi(): void
    {
        $dummyBase64Png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        // Submit absensi terlebih dahulu
        $this->actingAs($this->siswa)->post('/siswa/absensi', [
            'foto_wajah' => $dummyBase64Png,
            'tanda_tangan' => $dummyBase64Png,
            'status' => 'hadir',
        ]);

        // Submit laporan
        $response = $this->actingAs($this->siswa)->post('/siswa/laporan', [
            'rencana_tugas' => 'Mengerjakan konfigurasi router mikrotik laboratorium.',
            'catatan' => 'Semua port berjalan normal.',
        ]);

        $response->assertRedirect(route('siswa.dashboard'));

        $this->assertDatabaseHas('laporan_harian', [
            'siswa_id' => $this->siswa->id,
            'rencana_tugas' => 'Mengerjakan konfigurasi router mikrotik laboratorium.',
            'status' => 'terkirim',
        ]);
    }

    public function test_riwayat_and_profil_pages_render_correctly(): void
    {
        $responseRiwayat = $this->actingAs($this->siswa)->get('/siswa/riwayat');
        $responseRiwayat->assertStatus(200);

        $responseProfil = $this->actingAs($this->siswa)->get('/siswa/profil');
        $responseProfil->assertStatus(200);
        $responseProfil->assertSee('Nathan Hall');
    }

    public function test_teacher_can_access_monitoring_dashboard_and_student_detail(): void
    {
        $response = $this->actingAs($this->guru)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Monitoring Aktivitas Siswa PKL');
        $response->assertSee('Nathan Hall');

        $responseDetail = $this->actingAs($this->guru)->get("/admin/siswa/{$this->siswa->id}");
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('Nathan Hall');
    }

    public function test_role_middleware_prevents_siswa_from_admin_dashboard(): void
    {
        $response = $this->actingAs($this->siswa)->get('/admin/dashboard');
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_admin_can_manage_tempat_pkl(): void
    {
        // View index
        $response = $this->actingAs($this->admin)->get('/admin/tempat-pkl');
        $response->assertStatus(200);
        $response->assertSee('Tempat PKL Industri Mitra');
        $response->assertSee('PT Telkom Indonesia');

        // Store new
        $responseStore = $this->actingAs($this->admin)->post('/admin/tempat-pkl', [
            'nama_perusahaan' => 'PT Astra Honda Motor',
            'bidang' => 'Otomotif & Manufaktur',
            'kota' => 'Jakarta Utara',
            'alamat' => 'Kawasan Industri Sunter',
            'kontak' => '021-6518080',
        ]);
        $responseStore->assertRedirect(route('admin.tempat-pkl.index'));
        $this->assertDatabaseHas('tempat_pkl', [
            'nama_perusahaan' => 'PT Astra Honda Motor',
            'kota' => 'Jakarta Utara',
        ]);

        $astra = TempatPkl::where('nama_perusahaan', 'PT Astra Honda Motor')->first();

        // Update
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/tempat-pkl/{$astra->id}", [
            'nama_perusahaan' => 'PT Astra Honda Motor Tbk',
            'bidang' => 'Otomotif & Robotik',
            'kota' => 'Jakarta Utara',
            'alamat' => 'Kawasan Industri Sunter 1',
            'kontak' => '021-6518081',
        ]);
        $responseUpdate->assertRedirect(route('admin.tempat-pkl.index'));
        $this->assertDatabaseHas('tempat_pkl', [
            'id' => $astra->id,
            'nama_perusahaan' => 'PT Astra Honda Motor Tbk',
        ]);

        // Delete
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/tempat-pkl/{$astra->id}");
        $responseDelete->assertRedirect(route('admin.tempat-pkl.index'));
        $this->assertDatabaseMissing('tempat_pkl', [
            'id' => $astra->id,
        ]);
    }

    public function test_admin_can_manage_siswa_and_penempatan(): void
    {
        // View index
        $response = $this->actingAs($this->admin)->get('/admin/kelola-siswa');
        $response->assertStatus(200);
        $response->assertSee('Data Siswa & Penempatan PKL', false);
        $response->assertSee('Nathan Hall');

        // Store new student
        $responseStore = $this->actingAs($this->admin)->post('/admin/kelola-siswa', [
            'name' => 'Dewi Sartika',
            'username' => 'dewi_sartika',
            'email' => 'dewi@sekolah.sch.id',
            'password' => 'password123',
            'nisn' => '0089876543',
            'kelas' => 'XI RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
        ]);
        $responseStore->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'dewi_sartika',
            'nisn' => '0089876543',
            'tempat_pkl_id' => $this->tempatPkl->id,
        ]);

        $dewi = User::where('username', 'dewi_sartika')->first();

        // Update student
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/kelola-siswa/{$dewi->id}", [
            'name' => 'Dewi Sartika Putri',
            'username' => 'dewi_sartika',
            'email' => 'dewi@sekolah.sch.id',
            'nisn' => '0089876543',
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tempat_pkl_id' => $this->tempatPkl->id,
            'guru_id' => $this->guru->id,
        ]);
        $responseUpdate->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseHas('users', [
            'id' => $dewi->id,
            'name' => 'Dewi Sartika Putri',
            'kelas' => 'XII RPL 1',
        ]);

        // Delete student
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/kelola-siswa/{$dewi->id}");
        $responseDelete->assertRedirect(route('admin.siswa.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $dewi->id,
        ]);
    }

    public function test_admin_can_manage_guru(): void
    {
        // View index
        $response = $this->actingAs($this->admin)->get('/admin/kelola-guru');
        $response->assertStatus(200);
        $response->assertSee('Data Guru Pembimbing PKL');
        $response->assertSee('Bapak Andi Pratama');

        // Store new teacher
        $responseStore = $this->actingAs($this->admin)->post('/admin/kelola-guru', [
            'name' => 'Ibu Siti Aminah, M.Kom.',
            'username' => 'siti_aminah',
            'email' => 'siti@sekolah.sch.id',
            'password' => 'password123',
            'nip' => '199002102015032001',
        ]);
        $responseStore->assertRedirect(route('admin.guru.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'siti_aminah',
            'role' => 'guru',
        ]);

        $siti = User::where('username', 'siti_aminah')->first();

        // Update teacher
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/kelola-guru/{$siti->id}", [
            'name' => 'Ibu Siti Aminah, M.Kom., Ph.D.',
            'username' => 'siti_aminah',
            'email' => 'siti.phd@sekolah.sch.id',
            'nip' => '199002102015032001',
        ]);
        $responseUpdate->assertRedirect(route('admin.guru.index'));
        $this->assertDatabaseHas('users', [
            'id' => $siti->id,
            'name' => 'Ibu Siti Aminah, M.Kom., Ph.D.',
        ]);

        // Delete teacher
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/kelola-guru/{$siti->id}");
        $responseDelete->assertRedirect(route('admin.guru.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $siti->id,
        ]);
    }

    public function test_admin_can_manage_admin_users(): void
    {
        // View index
        $response = $this->actingAs($this->admin)->get('/admin/kelola-admin');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Akun Administrator');
        $response->assertSee('Administrator PKL');

        // Store new admin
        $responseStore = $this->actingAs($this->admin)->post('/admin/kelola-admin', [
            'name' => 'Wakil Kurikulum PKL',
            'username' => 'waka_kurikulum',
            'email' => 'kurikulum@sekolah.sch.id',
            'nip' => '197508102000031004',
            'password' => 'adminpassword123',
        ]);
        $responseStore->assertRedirect(route('admin.kelola-admin.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'waka_kurikulum',
            'role' => 'admin',
        ]);

        $newAdmin = User::where('username', 'waka_kurikulum')->first();

        // Update admin
        $responseUpdate = $this->actingAs($this->admin)->put("/admin/kelola-admin/{$newAdmin->id}", [
            'name' => 'Wakil Kepala Sekolah Bidang Kurikulum',
            'username' => 'waka_kurikulum',
            'email' => 'waka.kurikulum@sekolah.sch.id',
            'nip' => '197508102000031004',
        ]);
        $responseUpdate->assertRedirect(route('admin.kelola-admin.index'));
        $this->assertDatabaseHas('users', [
            'id' => $newAdmin->id,
            'name' => 'Wakil Kepala Sekolah Bidang Kurikulum',
        ]);

        // Admin cannot delete their own account
        $responseSelfDelete = $this->actingAs($this->admin)->delete("/admin/kelola-admin/{$this->admin->id}");
        $responseSelfDelete->assertRedirect(route('admin.kelola-admin.index'));
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        // Admin can delete other admin
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/kelola-admin/{$newAdmin->id}");
        $responseDelete->assertRedirect(route('admin.kelola-admin.index'));
        $this->assertDatabaseMissing('users', ['id' => $newAdmin->id]);
    }

    public function test_admin_can_manage_periode_pkl(): void
    {
        // View index
        $response = $this->actingAs($this->admin)->get('/admin/periode');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Periode PKL');

        // Store new period
        $responseStore = $this->actingAs($this->admin)->post('/admin/periode', [
            'nama_periode' => 'PKL Semester Genap 2027',
            'tanggal_mulai' => '2027-01-05',
            'tanggal_selesai' => '2027-04-10',
            'is_aktif' => '1',
        ]);
        $responseStore->assertRedirect(route('admin.periode.index'));
        $this->assertDatabaseHas('periode_pkl', [
            'nama_periode' => 'PKL Semester Genap 2027',
            'is_aktif' => true,
        ]);

        $periodeBaru = PeriodePkl::where('nama_periode', 'PKL Semester Genap 2027')->first();

        // Set previous period active
        $periodeLama = PeriodePkl::where('nama_periode', 'PKL Semester Ganjil 2026')->first();
        $responseSetAktif = $this->actingAs($this->admin)->post("/admin/periode/{$periodeLama->id}/set-aktif");
        $responseSetAktif->assertRedirect(route('admin.periode.index'));
        $this->assertTrue($periodeLama->fresh()->is_aktif);
        $this->assertFalse($periodeBaru->fresh()->is_aktif);

        // Delete period
        $responseDelete = $this->actingAs($this->admin)->delete("/admin/periode/{$periodeBaru->id}");
        $responseDelete->assertRedirect(route('admin.periode.index'));
        $this->assertDatabaseMissing('periode_pkl', [
            'id' => $periodeBaru->id,
        ]);
    }

    public function test_admin_and_guru_can_access_rekap_and_export_csv(): void
    {
        // Create attendance record
        $absensi = Absensi::create([
            'siswa_id' => $this->siswa->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam' => '07:45:00',
            'foto_wajah' => 'storage/absensi/foto/sample.jpg',
            'tanda_tangan' => 'storage/absensi/ttd/sample.png',
            'status' => 'hadir',
        ]);

        LaporanHarian::create([
            'siswa_id' => $this->siswa->id,
            'absensi_id' => $absensi->id,
            'tanggal' => Carbon::today()->toDateString(),
            'rencana_tugas' => 'Mengerjakan instalasi server Linux Debian',
            'catatan' => 'Selesai tanpa kendala',
            'status' => 'terkirim',
        ]);

        // Rekap index view by Guru
        $responseIndex = $this->actingAs($this->guru)->get('/admin/rekap');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Rekapitulasi Presensi', false);
        $responseIndex->assertSee('Nathan Hall');

        // Cetak print view by Admin
        $responseCetak = $this->actingAs($this->admin)->get('/admin/rekap/cetak');
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('Rekapitulasi Kehadiran', false);
        $responseCetak->assertSee('Nathan Hall');

        // CSV export
        $responseCsv = $this->actingAs($this->admin)->get('/admin/rekap/export-csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_guru_cannot_access_admin_crud_routes(): void
    {
        // Guru trying to access /admin/tempat-pkl should be redirected to admin.dashboard
        $response = $this->actingAs($this->guru)->get('/admin/tempat-pkl');
        $response->assertRedirect(route('admin.dashboard'));

        // Guru trying to access /admin/kelola-siswa should be redirected
        $responseSiswa = $this->actingAs($this->guru)->get('/admin/kelola-siswa');
        $responseSiswa->assertRedirect(route('admin.dashboard'));

        // Guru trying to access /admin/kelola-admin should be redirected
        $responseAdmin = $this->actingAs($this->guru)->get('/admin/kelola-admin');
        $responseAdmin->assertRedirect(route('admin.dashboard'));
    }

    public function test_unassigned_student_dashboard_and_profile_show_fallback_labels(): void
    {
        $siswaBaru = User::create([
            'name' => 'Siswa Baru Tanpa Penempatan',
            'username' => 'siswa_baru',
            'email' => 'siswabaru@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'nisn' => '0098765432',
            'kelas' => 'XI RPL 2',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tempat_pkl_id' => null,
            'guru_id' => null,
        ]);

        $responseDash = $this->actingAs($siswaBaru)->get('/siswa/dashboard');
        $responseDash->assertStatus(200);
        $responseDash->assertSee('Belum Ditempatkan');

        $responseProfil = $this->actingAs($siswaBaru)->get('/siswa/profil');
        $responseProfil->assertStatus(200);
        $responseProfil->assertSee('Belum Ditempatkan');
        $responseProfil->assertSee('Belum Ditugaskan');
    }

    public function test_admin_detail_siswa_renders_dynamic_name_when_no_attendance(): void
    {
        $siswaBaru = User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi_santoso',
            'email' => 'budi@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'siswa',
            'nisn' => '0091122334',
            'kelas' => 'XI TKJ 2',
            'jurusan' => 'Teknik Komputer dan Jaringan',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/siswa/{$siswaBaru->id}");
        $response->assertStatus(200);
        $response->assertSee('Siswa Budi Santoso belum melakukan absensi selfie wajah.');
    }

    public function test_siswa_can_submit_attendance_with_photo_only_without_signature(): void
    {
        $dummyBase64Png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

        $siswaLain = User::create([
            'name' => 'Siswa Test Tanpa TTD',
            'username' => 'siswa_not_ttd',
            'email' => 'siswa_not_ttd@sekolah.sch.id',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);

        $response = $this->actingAs($siswaLain)->post('/siswa/absensi', [
            'foto_wajah' => $dummyBase64Png,
            'status' => 'hadir',
        ]);

        $response->assertRedirect(route('siswa.laporan'));

        $absensi = Absensi::where('siswa_id', $siswaLain->id)->first();
        $this->assertNotNull($absensi);
        $this->assertNull($absensi->tanda_tangan);
    }

    public function test_absensi_foto_and_signature_url_accessors(): void
    {
        $absensi = new Absensi([
            'foto_wajah' => 'absensi/foto/sample.jpg',
            'tanda_tangan' => 'storage/absensi/ttd/sample.png',
        ]);

        $this->assertStringContainsString('storage/absensi/foto/sample.jpg', $absensi->foto_url);
        $this->assertStringContainsString('storage/absensi/ttd/sample.png', $absensi->ttd_url);
        // Ensure no double storage prefix like storage/storage/
        $this->assertStringNotContainsString('storage/storage/', $absensi->ttd_url);
    }

    public function test_admin_can_download_siswa_import_template(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.siswa.template'));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename="template_import_siswa.xls"', (string)$response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('urn:schemas-microsoft-com:office:spreadsheet', $response->getContent());
        $this->assertStringContainsString('nama_lengkap', $response->getContent());
    }

    public function test_admin_can_download_siswa_import_template_csv(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.siswa.template', ['format' => 'csv']));

        $response->assertStatus(200);
        $this->assertStringContainsString('attachment; filename="template_import_siswa.csv"', (string)$response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('nama_lengkap', $response->streamedContent());
    }

    public function test_admin_can_preview_valid_siswa_import_excel_file(): void
    {
        $excelXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n"
            . ' <Worksheet ss:Name="Sheet1">' . "\n"
            . '  <Table>' . "\n"
            . '   <Row>' . "\n"
            . '    <Cell><Data ss:Type="String">nama_lengkap</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">nisn</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">username</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">email</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">password</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">kelas</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">jurusan</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">tempat_pkl</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">guru_pembimbing</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row>' . "\n"
            . '    <Cell><Data ss:Type="String">Farhan Ramadhan</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">0085566778</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">farhan_r</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">farhan@sekolah.sch.id</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">secret123</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">XI TKJ 1</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">Teknik Komputer dan Jaringan</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">PT Telkom Indonesia</Data></Cell>' . "\n"
            . '    <Cell><Data ss:Type="String">Budi Santoso</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '  </Table>' . "\n"
            . ' </Worksheet>' . "\n"
            . '</Workbook>';

        $file = UploadedFile::fake()->createWithContent('template_import_siswa.xls', $excelXml);

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.import.preview'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Farhan Ramadhan');
        $response->assertSee('0085566778');
        $response->assertSee('farhan_r');
        $this->assertNotEmpty(session('pending_siswa_import'));
    }

    public function test_admin_can_preview_valid_siswa_import_file(): void
    {
        $csvContent = "nama_lengkap,nisn,username,email,password,kelas,jurusan,tempat_pkl,guru_pembimbing\n"
            . "Dewi Lestari,0089911223,dewi_lestari,dewi@sekolah.sch.id,secret123,XI TKJ 1,Teknik Komputer dan Jaringan,PT Telkom Indonesia,Budi Santoso";

        $file = UploadedFile::fake()->createWithContent('import_siswa.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.import.preview'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertSee('Dewi Lestari');
        $response->assertSee('0089911223');
        $response->assertSee('dewi_lestari');
        $this->assertNotEmpty(session('pending_siswa_import'));
    }

    public function test_admin_can_preview_siswa_import_with_validation_errors(): void
    {
        // Baris 1: duplikat NISN dengan siswa yang sudah ada di database ($this->siswa)
        // Baris 2: nama kosong
        $csvContent = "nama_lengkap,nisn,username,email,password,kelas,jurusan,tempat_pkl,guru_pembimbing\n"
            . "Duplikat Siswa,{$this->siswa->nisn},siswa_duplikat,,password123,XI TKJ 1,Teknik Komputer dan Jaringan,,\n"
            . ",0099887711,siswa_tanpa_nama,,password123,XI TKJ 1,Teknik Komputer dan Jaringan,,";

        $file = UploadedFile::fake()->createWithContent('import_error.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post(route('admin.siswa.import.preview'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertSee('sudah terdaftar di sistem');
        $response->assertSee('Nama lengkap wajib diisi.');
    }

    public function test_admin_can_confirm_bulk_import_and_accounts_are_created(): void
    {
        $csvContent = "nama_lengkap,nisn,username,email,password,kelas,jurusan,tempat_pkl,guru_pembimbing\n"
            . "Bambang Pamungkas,0077889911,bambang_p,bambang@sekolah.sch.id,pass1234,XI RPL 1,Rekayasa Perangkat Lunak,PT Telkom Indonesia,";

        $file = UploadedFile::fake()->createWithContent('import_confirm.csv', $csvContent);

        // Langkah 1: Upload & Preview
        $this->actingAs($this->admin)->post(route('admin.siswa.import.preview'), [
            'file' => $file,
        ]);

        $this->assertNotEmpty(session('pending_siswa_import'));

        // Langkah 2: Konfirmasi
        $response = $this->actingAs($this->admin)->post(route('admin.siswa.import.confirm'));

        $response->assertRedirect(route('admin.siswa.index'));
        $response->assertSessionHas('success');

        $created = User::where('nisn', '0077889911')->first();
        $this->assertNotNull($created);
        $this->assertEquals('Bambang Pamungkas', $created->name);
        $this->assertEquals('bambang_p', $created->username);
        $this->assertEquals('siswa', $created->role);
        $this->assertEquals($this->tempatPkl->id, $created->tempat_pkl_id);
    }

    public function test_non_admin_cannot_access_siswa_bulk_import(): void
    {
        $response = $this->actingAs($this->siswa)->get(route('admin.siswa.import'));
        $response->assertRedirect(route('siswa.dashboard'));
    }
}
