<?php
// File: app/Http/Controllers/RekapController.php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\TempatPkl;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RekapController extends Controller
{
    /**
     * Tampilkan Halaman Rekapitulasi Presensi & Jurnal Aktivitas PKL.
     */
    public function index(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

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
     * Ekspor Data Rekapitulasi ke Berkas CSV (Kompatibel Excel).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

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

        $records = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();

        $fileName = 'rekap_pkl_' . $startDate . '_sd_' . $endDate . '.csv';

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

            // Header Kolom CSV
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
            ]);

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
                ]);
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

        $records = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();
        $pembimbing = User::where('role', 'guru')->first();

        return view('admin.rekap.cetak', compact('records', 'startDate', 'endDate', 'pembimbing'));
    }
}
