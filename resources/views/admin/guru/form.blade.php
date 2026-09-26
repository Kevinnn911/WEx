<!-- File: resources/views/admin/guru/form.blade.php -->
@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit Data Guru' : 'Tambah Guru Baru') . ' — Sekolah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
  <!-- Back Button & Page Title -->
  <div class="flex items-center gap-3">
    <a href="{{ route('admin.guru.index') }}" class="p-2 rounded-xl bg-white border border-border-hairline text-text-secondary hover:text-on-surface hover:bg-background transition-colors">
      <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">{{ $isEdit ? 'Edit Guru Pembimbing' : 'Tambah Guru Pembimbing Baru' }}</h1>
      <p class="text-xs text-text-secondary mt-0.5">Lengkapi identitas guru pembimbing serta kredensial akun akses monitoring.</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 md:p-8">
    <form action="{{ $isEdit ? route('admin.guru.update', $guru->id) : route('admin.guru.store') }}" method="POST" class="space-y-5">
      @csrf
      @if($isEdit)
        @method('PUT')
      @endif

      <!-- Nama Guru -->
      <div>
        <label for="name" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          Nama Lengkap Guru (Beserta Gelar) <span class="text-status-error">*</span>
        </label>
        <input
          type="text"
          id="name"
          name="name"
          value="{{ old('name', $guru->name) }}"
          required
          placeholder="Contoh: Bapak Andi Pratama, S.Pd., M.T."
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- NIP -->
      <div>
        <label for="nip" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          NIP (Nomor Induk Pegawai)
        </label>
        <input
          type="text"
          id="nip"
          name="nip"
          value="{{ old('nip', $guru->nip) }}"
          placeholder="Contoh: 198503152010011002"
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Username & Email Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="username" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
            Username Login <span class="text-status-error">*</span>
          </label>
          <input
            type="text"
            id="username"
            name="username"
            value="{{ old('username', $guru->username) }}"
            required
            placeholder="Contoh: andi_pratama"
            class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
        </div>

        <div>
          <label for="email" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
            Alamat Email
          </label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $guru->email) }}"
            placeholder="Contoh: andi@sekolah.sch.id"
            class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
        </div>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          Kata Sandi / Password {{ $isEdit ? '(Kosongkan jika tidak ingin mengubah)' : '*' }}
        </label>
        <input
          type="password"
          id="password"
          name="password"
          {{ $isEdit ? '' : 'required' }}
          minlength="6"
          placeholder="Minimal 6 karakter"
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Actions Button -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-hairline">
        <a href="{{ route('admin.guru.index') }}" class="px-5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-background text-text-secondary font-bold text-xs transition-colors">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">save</span>
          <span>{{ $isEdit ? 'Simpan Perubahan' : 'Tambahkan Guru' }}</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
