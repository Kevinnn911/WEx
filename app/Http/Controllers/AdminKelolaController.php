<?php
// File: app/Http/Controllers/AdminKelolaController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminKelolaController extends Controller
{
    /**
     * Tampilkan daftar seluruh Administrator PKL Sekolah.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $adminList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();

        return view('admin.admin_user.index', compact('adminList'));
    }

    /**
     * Tampilkan formulir penambahan Administrator baru.
     */
    public function create(): View
    {
        return view('admin.admin_user.form', [
            'adminUser' => new User(),
            'isEdit' => false,
        ]);
    }

    /**
     * Simpan data Administrator baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'nip' => 'nullable|string|max:50|unique:users,nip',
            'password' => 'required|string|min:6',
        ]);

        $validated['role'] = 'admin';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Data administrator baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit Administrator.
     */
    public function edit(int $id): View
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        return view('admin.admin_user.form', [
            'adminUser' => $adminUser,
            'isEdit' => true,
        ]);
    }

    /**
     * Perbarui data Administrator.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'nip' => 'nullable|string|max:50|unique:users,nip,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $adminUser->update($validated);

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Data administrator berhasil diperbarui.');
    }

    /**
     * Hapus akun Administrator dengan proteksi akun aktif & kuota minimum.
     */
    public function destroy(int $id): RedirectResponse
    {
        $adminUser = User::where('role', 'admin')->findOrFail($id);

        if ($adminUser->id === Auth::id()) {
            return redirect()->route('admin.kelola-admin.index')
                ->with('error', 'Tidak dapat menghapus akun administrator yang sedang aktif digunakan.');
        }

        if (User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.kelola-admin.index')
                ->with('error', 'Tidak dapat menghapus akun karena sistem memerlukan minimal satu administrator.');
        }

        $adminUser->delete();

        return redirect()->route('admin.kelola-admin.index')
            ->with('success', 'Akun administrator berhasil dihapus.');
    }
}
