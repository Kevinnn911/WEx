<?php
// File: app/Http/Controllers/TempatPklController.php

namespace App\Http\Controllers;

use App\Models\TempatPkl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TempatPklController extends Controller
{
    /**
     * Tampilkan daftar seluruh Tempat PKL / Industri Mitra.
     */
    public function index(Request $request): View
    {
        $query = TempatPkl::withCount('siswa');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('bidang', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        $tempatPklList = $query->orderBy('nama_perusahaan', 'asc')->paginate(10)->withQueryString();

        return view('admin.tempat_pkl.index', compact('tempatPklList'));
    }

    /**
     * Tampilkan formulir penambahan Tempat PKL baru.
     */
    public function create(): View
    {
        return view('admin.tempat_pkl.form', [
            'tempatPkl' => new TempatPkl(),
            'isEdit' => false,
        ]);
    }

    /**
     * Simpan data Tempat PKL baru ke basis data.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
        ]);

        TempatPkl::create($validated);

        return redirect()->route('admin.tempat-pkl.index')
            ->with('success', 'Data tempat PKL industri berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir perbaikan/edit data Tempat PKL.
     */
    public function edit(int $id): View
    {
        $tempatPkl = TempatPkl::findOrFail($id);

        return view('admin.tempat_pkl.form', [
            'tempatPkl' => $tempatPkl,
            'isEdit' => true,
        ]);
    }

    /**
     * Perbarui data Tempat PKL dalam basis data.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $tempatPkl = TempatPkl::findOrFail($id);

        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang' => 'nullable|string|max:255',
            'kota' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:100',
        ]);

        $tempatPkl->update($validated);

        return redirect()->route('admin.tempat-pkl.index')
            ->with('success', 'Data tempat PKL industri berhasil diperbarui.');
    }

    /**
     * Hapus data Tempat PKL.
     */
    public function destroy(int $id): RedirectResponse
    {
        $tempatPkl = TempatPkl::withCount('siswa')->findOrFail($id);

        if ($tempatPkl->siswa_count > 0) {
            return redirect()->route('admin.tempat-pkl.index')
                ->with('error', 'Tidak dapat menghapus tempat PKL karena masih memiliki siswa yang ditempatkan.');
        }

        $tempatPkl->delete();

        return redirect()->route('admin.tempat-pkl.index')
            ->with('success', 'Data tempat PKL berhasil dihapus.');
    }
}
