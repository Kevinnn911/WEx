<?php
// File: app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LaporanHarian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan Dashboard Monitoring PKL Sekolah (Guru & Admin).
     */
    public function dashboard(Request $request): View
    {
        $today = Carbon::today()->toDateString();
        $currentUser = Auth::user();

        // Query siswa: jika guru, prioritaskan siswa bimbingannya
        $query = User::where('role', 'siswa')
            ->with(['tempatPkl', 'guruPembimbing', 'absensi' => function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            }, 'laporanHarian' => function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            }]);

        if ($currentUser->isGuru() && $request->get('filter_scope') === 'bimbingan_saya') {
            $query->where('guru_id', $currentUser->id);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->get('kelas'));
        }

        $siswaList = $query->orderBy('name', 'asc')->get();

        // Hitung metrik KPI hari ini
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalHadir = Absensi::whereDate('tanggal', $today)->where('status', 'hadir')->count();
        $totalBelumHadir = max(0, $totalSiswa - $totalHadir);
        $totalLaporan = LaporanHarian::whereDate('tanggal', $today)->count();

        // Daftar kelas untuk filter dropdown
        $daftarKelas = User::where('role', 'siswa')
            ->whereNotNull('kelas')
            ->distinct()
            ->pluck('kelas');

        return view('admin.dashboard', compact(
            'currentUser',
            'siswaList',
            'totalSiswa',
            'totalHadir',
            'totalBelumHadir',
            'totalLaporan',
            'daftarKelas',
            'today'
        ));
    }

    /**
     * Tampilkan Halaman Detail Siswa PKL (Peninjauan Bukti & Aktivitas).
     */
    public function detailSiswa(int $id): View
    {
        $siswa = User::where('role', 'siswa')
            ->with(['tempatPkl', 'guruPembimbing'])
            ->findOrFail($id);

        $today = Carbon::today()->toDateString();

        $absensiHariIni = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        $laporanHariIni = LaporanHarian::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $today)
            ->first();

        $riwayatAktivitas = Absensi::where('siswa_id', $siswa->id)
            ->with('laporan')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.detail_siswa', compact(
            'siswa',
            'absensiHariIni',
            'laporanHariIni',
            'riwayatAktivitas',
            'today'
        ));
    }
}
