<?php
// File: app/Http/Controllers/GuruKelolaController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuruKelolaController extends Controller
{
    /**
     * Tampilkan daftar seluruh Guru Pembimbing PKL.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'guru')
            ->withCount('siswaBimbingan');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $guruList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.guru.index', compact('guruList'));
    }

    /**
     * Tampilkan formulir penambahan Guru Pembimbing baru.
     */
    public function create(): View
    {
        return view('admin.guru.form', [
            'guru' => new User(),
            'isEdit' => false,
        ]);
    }

    /**
     * Simpan data Guru Pembimbing baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nip' => 'nullable|string|max:50|unique:users,nip',
        ]);

        $validated['role'] = 'guru';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru pembimbing berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit data Guru Pembimbing.
     */
    public function edit(int $id): View
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        return view('admin.guru.form', [
            'guru' => $guru,
            'isEdit' => true,
        ]);
    }

    /**
     * Perbarui data Guru Pembimbing.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $id,
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $guru->update($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru pembimbing berhasil diperbarui.');
    }

    /**
     * Hapus data Guru Pembimbing.
     */
    public function destroy(int $id): RedirectResponse
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        // Lepas relasi pembimbing dari siswa terkait
        User::where('guru_id', $guru->id)->update(['guru_id' => null]);
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru pembimbing berhasil dihapus.');
    }
}
