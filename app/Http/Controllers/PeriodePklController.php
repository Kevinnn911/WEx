<?php
// File: app/Http/Controllers/PeriodePklController.php

namespace App\Http\Controllers;

use App\Models\PeriodePkl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodePklController extends Controller
{
    /**
     * Tampilkan daftar seluruh Periode PKL.
     */
    public function index(): View
    {
        $periodeList = PeriodePkl::orderBy('tanggal_mulai', 'desc')->get();

        return view('admin.periode.index', compact('periodeList'));
    }

    /**
     * Simpan Periode PKL baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $isAktif = $request->boolean('is_aktif', false);

        if ($isAktif) {
            PeriodePkl::query()->update(['is_aktif' => false]);
        }

        $validated['is_aktif'] = $isAktif;

        PeriodePkl::create($validated);

        return redirect()->route('admin.periode.index')
            ->with('success', 'Periode PKL baru berhasil ditambahkan.');
    }

    /**
     * Jadikan periode ini sebagai periode aktif utama.
     */
    public function setAktif(int $id): RedirectResponse
    {
        $periode = PeriodePkl::findOrFail($id);

        // Nonaktifkan semua periode lain
        PeriodePkl::query()->update(['is_aktif' => false]);

        // Aktifkan periode yang dipilih
        $periode->update(['is_aktif' => true]);

        return redirect()->route('admin.periode.index')
            ->with('success', "Periode '{$periode->nama_periode}' kini berstatus aktif.");
    }

    /**
     * Hapus data Periode PKL.
     */
    public function destroy(int $id): RedirectResponse
    {
        $periode = PeriodePkl::findOrFail($id);
        $periode->delete();

        return redirect()->route('admin.periode.index')
            ->with('success', 'Data periode PKL berhasil dihapus.');
    }
}
