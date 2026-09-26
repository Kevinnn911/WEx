<?php
// File: routes/web.php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKelolaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuruKelolaController;
use App\Http\Controllers\PeriodePklController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaKelolaController;
use App\Http\Controllers\TempatPklController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Monitoring Siswa PKL
|--------------------------------------------------------------------------
*/

// Rute Publik & Autentikasi
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Khusus Siswa (Mobile-First)
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/absensi', [SiswaController::class, 'absensiForm'])->name('absensi');
    Route::post('/absensi', [SiswaController::class, 'submitAbsensi'])->name('absensi.submit');
    Route::get('/laporan', [SiswaController::class, 'laporanForm'])->name('laporan');
    Route::post('/laporan', [SiswaController::class, 'submitLaporan'])->name('laporan.submit');
    Route::get('/riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/profil', [SiswaController::class, 'profil'])->name('profil');
});

// Rute Monitoring & Rekap (Dapat diakses Guru & Admin)
Route::middleware(['auth', 'role:guru,admin'])->prefix('admin')->name('admin.')->group(function () {
    // Monitoring Dashboard & Detail Siswa
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa/{id}', [AdminController::class, 'detailSiswa'])->name('siswa.detail');

    // Rekapitulasi & Ekspor Laporan
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/export-csv', [RekapController::class, 'exportCsv'])->name('rekap.export-csv');
    Route::get('/rekap/cetak', [RekapController::class, 'cetak'])->name('rekap.cetak');
});

// Rute Kelola Master Data PKL & Akun (Khusus Admin Sekolah)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Kelola Tempat PKL / Industri Mitra
    Route::get('/tempat-pkl', [TempatPklController::class, 'index'])->name('tempat-pkl.index');
    Route::get('/tempat-pkl/create', [TempatPklController::class, 'create'])->name('tempat-pkl.create');
    Route::post('/tempat-pkl', [TempatPklController::class, 'store'])->name('tempat-pkl.store');
    Route::get('/tempat-pkl/{id}/edit', [TempatPklController::class, 'edit'])->name('tempat-pkl.edit');
    Route::put('/tempat-pkl/{id}', [TempatPklController::class, 'update'])->name('tempat-pkl.update');
    Route::delete('/tempat-pkl/{id}', [TempatPklController::class, 'destroy'])->name('tempat-pkl.destroy');

    // Kelola Data Siswa & Penempatan
    Route::get('/kelola-siswa', [SiswaKelolaController::class, 'index'])->name('siswa.index');
    Route::get('/kelola-siswa/template', [SiswaKelolaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::get('/kelola-siswa/import', [SiswaKelolaController::class, 'importForm'])->name('siswa.import');
    Route::post('/kelola-siswa/import/preview', [SiswaKelolaController::class, 'importPreview'])->name('siswa.import.preview');
    Route::post('/kelola-siswa/import/confirm', [SiswaKelolaController::class, 'importConfirm'])->name('siswa.import.confirm');
    Route::get('/kelola-siswa/create', [SiswaKelolaController::class, 'create'])->name('siswa.create');
    Route::post('/kelola-siswa', [SiswaKelolaController::class, 'store'])->name('siswa.store');
    Route::get('/kelola-siswa/{id}/edit', [SiswaKelolaController::class, 'edit'])->name('siswa.edit');
    Route::put('/kelola-siswa/{id}', [SiswaKelolaController::class, 'update'])->name('siswa.update');
    Route::delete('/kelola-siswa/{id}', [SiswaKelolaController::class, 'destroy'])->name('siswa.destroy');

    // Kelola Data Guru Pembimbing
    Route::get('/kelola-guru', [GuruKelolaController::class, 'index'])->name('guru.index');
    Route::get('/kelola-guru/create', [GuruKelolaController::class, 'create'])->name('guru.create');
    Route::post('/kelola-guru', [GuruKelolaController::class, 'store'])->name('guru.store');
    Route::get('/kelola-guru/{id}/edit', [GuruKelolaController::class, 'edit'])->name('guru.edit');
    Route::put('/kelola-guru/{id}', [GuruKelolaController::class, 'update'])->name('guru.update');
    Route::delete('/kelola-guru/{id}', [GuruKelolaController::class, 'destroy'])->name('guru.destroy');

    // Kelola Akun Administrator
    Route::get('/kelola-admin', [AdminKelolaController::class, 'index'])->name('kelola-admin.index');
    Route::get('/kelola-admin/create', [AdminKelolaController::class, 'create'])->name('kelola-admin.create');
    Route::post('/kelola-admin', [AdminKelolaController::class, 'store'])->name('kelola-admin.store');
    Route::get('/kelola-admin/{id}/edit', [AdminKelolaController::class, 'edit'])->name('kelola-admin.edit');
    Route::put('/kelola-admin/{id}', [AdminKelolaController::class, 'update'])->name('kelola-admin.update');
    Route::delete('/kelola-admin/{id}', [AdminKelolaController::class, 'destroy'])->name('kelola-admin.destroy');

    // Kelola Periode PKL
    Route::get('/periode', [PeriodePklController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodePklController::class, 'store'])->name('periode.store');
    Route::post('/periode/{id}/set-aktif', [PeriodePklController::class, 'setAktif'])->name('periode.set-aktif');
    Route::delete('/periode/{id}', [PeriodePklController::class, 'destroy'])->name('periode.destroy');
});
