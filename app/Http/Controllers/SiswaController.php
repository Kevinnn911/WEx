<?php
// File: app/Http/Controllers/SiswaController.php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LaporanHarian;
use App\Models\PeriodePkl;
use App\Services\GoogleDriveService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Siswa.
     */
    public function dashboard(): View
    {
        $user = Auth::user()->load(['tempatPkl', 'guruPembimbing']);
        $today = Carbon::today()->toDateString();

        $absensiHariIni = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $laporanHariIni = LaporanHarian::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Hitung hari ke-n PKL dari periode aktif
        $periode = PeriodePkl::where('is_aktif', true)->first();
        $hariKe = 1;
        $totalHari = 90;
        if ($periode) {
            $startDate = Carbon::parse($periode->tanggal_mulai);
            $currentDate = Carbon::today();
            if ($currentDate->greaterThanOrEqualTo($startDate)) {
                $hariKe = $startDate->diffInDays($currentDate) + 1;
            }
            $totalHari = $startDate->diffInDays(Carbon::parse($periode->tanggal_selesai)) + 1;
        }

        // Hitung total kehadiran
        $totalKehadiran = Absensi::where('siswa_id', $user->id)
            ->where('status', 'hadir')
            ->count();

        // Sapaan dinamis berdasarkan jam
        $hour = (int) Carbon::now()->format('H');
        if ($hour >= 4 && $hour < 11) {
            $salam = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $salam = 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $salam = 'Selamat Sore';
        } else {
            $salam = 'Selamat Malam';
        }

        return view('siswa.dashboard', compact(
            'user',
            'absensiHariIni',
            'laporanHariIni',
            'hariKe',
            'totalHari',
            'totalKehadiran',
            'salam'
        ));
    }

    /**
     * Tampilkan formulir Absensi Hari Ini.
     */
    public function absensiForm(): View|RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $absensiHariIni = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('siswa.absensi', compact('user', 'absensiHariIni'));
    }

    /**
     * Proses penyimpanan data absensi siswa.
     */
    public function submitAbsensi(Request $request, GoogleDriveService $driveService): RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Cegah duplikasi absensi hari ini
        $existing = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing) {
            return redirect()->route('siswa.absensi')->with('warning', 'Anda sudah melakukan absensi untuk hari ini.');
        }

        $request->validate([
            'foto_wajah' => ['required', 'string'],
            'status' => ['nullable', 'in:hadir,izin,sakit'],
        ], [
            'foto_wajah.required' => 'Foto selfie wajah wajib diambil sebelum mengirimkan absensi.',
        ]);

        // Simpan foto selfie ke Google Drive (atau fallback lokal)
        $fotoPath = $driveService->storeAbsensiMedia($request->input('foto_wajah'), 'foto', $user, $today);
        $ttdPath = $request->filled('tanda_tangan')
            ? $driveService->storeAbsensiMedia($request->input('tanda_tangan'), 'ttd', $user, $today)
            : null;

        Absensi::create([
            'siswa_id' => $user->id,
            'tanggal' => $today,
            'jam' => Carbon::now()->format('H:i:s'),
            'foto_wajah' => $fotoPath,
            'tanda_tangan' => $ttdPath,
            'status' => $request->input('status', 'hadir'),
        ]);

        return redirect()->route('siswa.laporan')->with('success', 'Absensi berhasil dikirim pada ' . Carbon::now()->format('H:i') . ' WIB! Silakan lanjutkan dengan mengisi laporan harian.');
    }

    /**
     * Tampilkan formulir Laporan Harian.
     */
    public function laporanForm(): View|RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $absensiHariIni = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensiHariIni) {
            return redirect()->route('siswa.absensi')->with('warning', 'Anda wajib melakukan absensi terlebih dahulu sebelum mengisi laporan rencana tugas harian.');
        }

        $laporanHariIni = LaporanHarian::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return view('siswa.laporan', compact('user', 'absensiHariIni', 'laporanHariIni'));
    }

    /**
     * Simpan pengisian laporan harian.
     */
    public function submitLaporan(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $absensiHariIni = Absensi::where('siswa_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        if (!$absensiHariIni) {
            return redirect()->route('siswa.absensi')->with('error', 'Wajib melakukan absensi terlebih dahulu.');
        }

        $request->validate([
            'rencana_tugas' => ['required', 'string', 'min:5'],
            'catatan' => ['nullable', 'string'],
        ], [
            'rencana_tugas.required' => 'Rencana pekerjaan harian wajib diisi.',
            'rencana_tugas.min' => 'Rencana pekerjaan minimal terdiri dari 5 karakter.',
        ]);

        LaporanHarian::updateOrCreate(
            [
                'siswa_id' => $user->id,
                'tanggal' => $today,
            ],
            [
                'absensi_id' => $absensiHariIni->id,
                'rencana_tugas' => $request->input('rencana_tugas'),
                'catatan' => $request->input('catatan'),
                'status' => 'terkirim',
            ]
        );

        return redirect()->route('siswa.dashboard')->with('success', 'Laporan harian berhasil dikirim dan tersimpan.');
    }

    /**
     * Tampilkan riwayat kehadiran dan laporan siswa.
     */
    public function riwayat(Request $request): View
    {
        $user = Auth::user();

        $riwayatList = Absensi::where('siswa_id', $user->id)
            ->with('laporan')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.riwayat', compact('user', 'riwayatList'));
    }

    /**
     * Tampilkan profil siswa dan informasi PKL.
     */
    public function profil(): View
    {
        $user = Auth::user()->load(['tempatPkl', 'guruPembimbing']);
        $periode = PeriodePkl::where('is_aktif', true)->first();

        return view('siswa.profil', compact('user', 'periode'));
    }
}
