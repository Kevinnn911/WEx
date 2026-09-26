<?php
// File: app/Http/Controllers/RekapController.php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\TempatPkl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    /**
     * Membangun query Eloquent untuk data presensi berdasarkan filter request.
     */
    private function buildFilterQuery(Request $request, string $startDate, string $endDate): Builder
    {
        $query = Absensi::with(['siswa.tempatPkl', 'siswa.guruPembimbing', 'laporan'])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate);

        if ($request->filled('kelas')) {
            $kelas = $request->get('kelas');
            $query->whereHas('siswa', function ($q) use ($kelas) {
                $q->where('kelas', $kelas);
            });
        }

        if ($request->filled('tempat_pkl_id')) {
            $tempatId = $request->get('tempat_pkl_id');
            $query->whereHas('siswa', function ($q) use ($tempatId) {
                $q->where('tempat_pkl_id', $tempatId);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        return $query;
    }

    /**
     * Tampilkan Halaman Rekapitulasi Presensi & Jurnal Aktivitas PKL.
     */
    public function index(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $query = $this->buildFilterQuery($request, $startDate, $endDate);
        $absensiList = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc')->paginate(15)->withQueryString();

        // Metrik Ringkasan
        $totalPresensi = Absensi::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->count();
        $totalHadir = Absensi::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->where('status', 'hadir')->count();
        $totalIzinSakit = Absensi::whereDate('tanggal', '>=', $startDate)->whereDate('tanggal', '<=', $endDate)->whereIn('status', ['izin', 'sakit'])->count();

        $daftarKelas = User::where('role', 'siswa')->whereNotNull('kelas')->distinct()->pluck('kelas');
        $tempatPklList = TempatPkl::orderBy('nama_perusahaan', 'asc')->get();

        return view('admin.rekap.index', compact(
            'absensiList',
            'startDate',
            'endDate',
            'totalPresensi',
            'totalHadir',
            'totalIzinSakit',
            'daftarKelas',
            'tempatPklList'
        ));
    }

    /**
     * Ekspor Data Rekapitulasi ke Berkas Spreadsheet Microsoft Excel (.xls).
     * Menggunakan format resmi SpreadsheetML XML dengan tata letak kolom rapi,
     * styling warna header institusional, border, auto-wrap text, dan proteksi format NISN teks.
     */
    public function exportExcel(Request $request): Response
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $records = $this->buildFilterQuery($request, $startDate, $endDate)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get();

        $escape = fn ($val) => htmlspecialchars((string) ($val ?? ''), ENT_XML1 | ENT_COMPAT, 'UTF-8');

        $totalHadir = 0;
        $totalIzinSakit = 0;

        $rowsXml = '';
        $no = 1;

        foreach ($records as $item) {
            $siswa = $item->siswa;
            $laporan = $item->laporan;
            $status = strtolower($item->status ?? 'hadir');

            if ($status === 'hadir') {
                $totalHadir++;
                $statusStyle = 'StatusHadir';
            } elseif (in_array($status, ['izin', 'sakit'], true)) {
                $totalIzinSakit++;
                $statusStyle = 'StatusIzinSakit';
            } else {
                $statusStyle = 'StatusAlfa';
            }

            $tanggalFormatted = $item->tanggal ? Carbon::parse($item->tanggal)->format('d/m/Y') : '-';
            $jamFormatted = $item->jam ? $item->jam . ' WIB' : '-';
            $nisnFormatted = $siswa ? $siswa->nisn : '-';
            $namaSiswa = $siswa ? $siswa->name : '-';
            $kelas = $siswa ? $siswa->kelas : '-';
            $jurusan = $siswa ? $siswa->jurusan : '-';
            $tempatPkl = ($siswa && $siswa->tempatPkl) ? $siswa->tempatPkl->nama_perusahaan : '-';
            $guruPembimbing = ($siswa && $siswa->guruPembimbing) ? $siswa->guruPembimbing->name : '-';
            $statusText = strtoupper($status);
            $rencanaTugas = $laporan ? $laporan->rencana_tugas : 'Belum mengisi laporan';
            $catatanLaporan = ($laporan && $laporan->catatan) ? $laporan->catatan : '-';

            $rowsXml .= '   <Row ss:Height="24">' . "\n"
                . '    <Cell ss:StyleID="CellCenter"><Data ss:Type="Number">' . $no++ . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellCenter"><Data ss:Type="String">' . $escape($tanggalFormatted) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellCenter"><Data ss:Type="String">' . $escape($jamFormatted) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellNisn"><Data ss:Type="String">' . $escape($nisnFormatted) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellLeft"><Data ss:Type="String">' . $escape($namaSiswa) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellCenter"><Data ss:Type="String">' . $escape($kelas) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellLeft"><Data ss:Type="String">' . $escape($jurusan) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellLeft"><Data ss:Type="String">' . $escape($tempatPkl) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellLeft"><Data ss:Type="String">' . $escape($guruPembimbing) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="' . $statusStyle . '"><Data ss:Type="String">' . $escape($statusText) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellWrap"><Data ss:Type="String">' . $escape($rencanaTugas) . '</Data></Cell>' . "\n"
                . '    <Cell ss:StyleID="CellWrap"><Data ss:Type="String">' . $escape($catatanLaporan) . '</Data></Cell>' . "\n"
                . '   </Row>' . "\n";
        }

        $periodeStr = Carbon::parse($startDate)->isoFormat('D MMMM Y') . ' s/d ' . Carbon::parse($endDate)->isoFormat('D MMMM Y');
        $waktuUnduhStr = Carbon::now()->isoFormat('D MMMM Y, HH:mm') . ' WIB';
        $totalRecord = count($records);

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n"
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n"
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n"
            . ' <Styles>' . "\n"
            . '  <Style ss:ID="Default" ss:Name="Normal">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Borders/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#000000"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="DocTitle">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="14" ss:Bold="1" ss:Color="#00626D"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="DocSubtitle">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Italic="1" ss:Color="#555555"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="MetaLabel">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#333333"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="MetaVal">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Color="#111111"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="HeaderCol">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#004D55"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#004D55"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#004D55"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#004D55"/>' . "\n"
            . '   </Borders>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>' . "\n"
            . '   <Interior ss:Color="#008294" ss:Pattern="Solid"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="CellCenter">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="CellLeft">' . "\n"
            . '   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="CellWrap">' . "\n"
            . '   <Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="CellNisn">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <NumberFormat ss:Format="@"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="StatusHadir">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#03543F"/>' . "\n"
            . '   <Interior ss:Color="#DEF7EC" ss:Pattern="Solid"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="StatusIzinSakit">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#713F12"/>' . "\n"
            . '   <Interior ss:Color="#FEF08A" ss:Pattern="Solid"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="StatusAlfa">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="10" ss:Bold="1" ss:Color="#991B1B"/>' . "\n"
            . '   <Interior ss:Color="#FEE2E2" ss:Pattern="Solid"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D1D5DB"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="SummaryRow">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#00626D"/>' . "\n"
            . '   <Interior ss:Color="#F0F7FB" ss:Pattern="Solid"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#008294"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#008294"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#008294"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#008294"/>' . "\n"
            . '   </Borders>' . "\n"
            . '  </Style>' . "\n"
            . ' </Styles>' . "\n"
            . ' <Worksheet ss:Name="Rekap Presensi PKL">' . "\n"
            . '  <Table>' . "\n"
            . '   <Column ss:Width="40"/>' . "\n"
            . '   <Column ss:Width="95"/>' . "\n"
            . '   <Column ss:Width="85"/>' . "\n"
            . '   <Column ss:Width="110"/>' . "\n"
            . '   <Column ss:Width="190"/>' . "\n"
            . '   <Column ss:Width="85"/>' . "\n"
            . '   <Column ss:Width="180"/>' . "\n"
            . '   <Column ss:Width="180"/>' . "\n"
            . '   <Column ss:Width="160"/>' . "\n"
            . '   <Column ss:Width="110"/>' . "\n"
            . '   <Column ss:Width="260"/>' . "\n"
            . '   <Column ss:Width="200"/>' . "\n"
            . '   <Row ss:Height="24">' . "\n"
            . '    <Cell ss:StyleID="DocTitle"><Data ss:Type="String">REKAPITULASI PRESENSI &amp; JURNAL AKTIVITAS SISWA PKL</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="18">' . "\n"
            . '    <Cell ss:StyleID="DocSubtitle"><Data ss:Type="String">SMK Pelita Nusantara — Sistem Monitoring Siswa PKL Terintegrasi</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="18">' . "\n"
            . '    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Rentang Periode:</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="MetaVal"><Data ss:Type="String">' . $escape($periodeStr) . '</Data></Cell>' . "\n"
            . '    <Cell ss:Index="4" ss:StyleID="MetaLabel"><Data ss:Type="String">Total Data:</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="MetaVal"><Data ss:Type="String">' . $totalRecord . ' Presensi (Hadir: ' . $totalHadir . ', Izin/Sakit: ' . $totalIzinSakit . ')</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="18">' . "\n"
            . '    <Cell ss:StyleID="MetaLabel"><Data ss:Type="String">Waktu Unduh:</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="MetaVal"><Data ss:Type="String">' . $escape($waktuUnduhStr) . '</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="12"/>' . "\n"
            . '   <Row ss:Height="28">' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">No</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Tanggal</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Jam Presensi</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">NISN</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Nama Lengkap Siswa</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Kelas</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Kompetensi Keahlian / Jurusan</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Tempat Industri / Instansi PKL</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Guru Pembimbing Sekolah</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Status Kehadiran</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Rencana Tugas / Jurnal Harian</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">Catatan Kendala / Tambahan</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . $rowsXml
            . '   <Row ss:Height="24">' . "\n"
            . '    <Cell ss:StyleID="SummaryRow"><Data ss:Type="String">Total</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="SummaryRow"><Data ss:Type="String">' . $totalRecord . ' Data</Data></Cell>' . "\n"
            . '    <Cell ss:Index="10" ss:StyleID="SummaryRow"><Data ss:Type="String">' . $totalHadir . ' Hadir | ' . $totalIzinSakit . ' Izin/Skt</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '  </Table>' . "\n"
            . ' </Worksheet>' . "\n"
            . '</Workbook>';

        $fileName = 'rekap_presensi_pkl_' . $startDate . '_sd_' . $endDate . '.xls';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Ekspor Data Rekapitulasi ke Berkas CSV (Kompatibel Excel Regional Indonesia).
     * Menggunakan UTF-8 BOM dan pemisah titik-koma ';' agar Excel langsung membuka kolom terpisah.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $records = $this->buildFilterQuery($request, $startDate, $endDate)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get();

        $fileName = 'rekap_presensi_pkl_' . $startDate . '_sd_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($records) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fputs($output, "\xEF\xBB\xBF");

            // Header Kolom CSV dengan pemisah titik-koma (standar Excel Indonesia & internasional)
            fputcsv($output, [
                'No',
                'Tanggal',
                'Jam Absensi',
                'NISN',
                'Nama Siswa',
                'Kelas',
                'Jurusan',
                'Tempat PKL',
                'Guru Pembimbing',
                'Status Kehadiran',
                'Rencana Tugas / Jurnal Harian',
                'Catatan Laporan'
            ], ';');

            $no = 1;
            foreach ($records as $item) {
                $siswa = $item->siswa;
                $laporan = $item->laporan;

                fputcsv($output, [
                    $no++,
                    $item->tanggal ? Carbon::parse($item->tanggal)->format('d/m/Y') : '-',
                    $item->jam ?? '-',
                    $siswa ? "'" . $siswa->nisn : '-',
                    $siswa ? $siswa->name : '-',
                    $siswa ? $siswa->kelas : '-',
                    $siswa ? $siswa->jurusan : '-',
                    $siswa && $siswa->tempatPkl ? $siswa->tempatPkl->nama_perusahaan : '-',
                    $siswa && $siswa->guruPembimbing ? $siswa->guruPembimbing->name : '-',
                    strtoupper($item->status ?? 'hadir'),
                    $laporan ? str_replace(["\r", "\n"], ' ', $laporan->rencana_tugas) : 'Belum mengirim laporan',
                    $laporan && $laporan->catatan ? str_replace(["\r", "\n"], ' ', $laporan->catatan) : '-'
                ], ';');
            }

            fclose($output);
        }, 200, $headers);
    }

    /**
     * Tampilkan Halaman Cetak Dokumen Resmi (Format Print A4).
     */
    public function cetak(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        $records = $this->buildFilterQuery($request, $startDate, $endDate)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam', 'asc')
            ->get();

        $pembimbing = User::where('role', 'guru')->first();

        return view('admin.rekap.cetak', compact('records', 'startDate', 'endDate', 'pembimbing'));
    }
}
