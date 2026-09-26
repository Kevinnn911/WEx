<!-- File: resources/views/admin/siswa/form.blade.php -->
@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit Data Siswa' : 'Tambah Siswa Baru') . ' — Sekolah')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  <!-- Back Button & Page Title -->
  <div class="flex items-center gap-3">
    <a href="{{ route('admin.siswa.index') }}" class="p-2 rounded-xl bg-white border border-border-hairline text-text-secondary hover:text-on-surface hover:bg-background transition-colors">
      <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">{{ $isEdit ? 'Edit Data Siswa' : 'Tambah Siswa & Penempatan PKL' }}</h1>
      <p class="text-xs text-text-secondary mt-0.5">Lengkapi identitas siswa, akun login sistem, serta alokasi tempat industri dan pembimbing.</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 md:p-8">
    <form action="{{ $isEdit ? route('admin.siswa.update', $siswa->id) : route('admin.siswa.store') }}" method="POST" class="space-y-6">
      @csrf
      @if($isEdit)
        @method('PUT')
      @endif

      <!-- Section 1: Identitas Siswa -->
      <div>
        <h2 class="text-sm font-extrabold text-on-surface flex items-center gap-2 mb-4 pb-2 border-b border-border-hairline">
          <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
          <span>1. Identitas Pribadi & Sekolah</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Nama Lengkap -->
          <div class="md:col-span-2">
            <label for="name" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Nama Lengkap Siswa <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="name"
              name="name"
              value="{{ old('name', $siswa->name) }}"
              required
              placeholder="Contoh: Nathan Hall"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <!-- NISN -->
          <div>
            <label for="nisn" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              NISN Siswa <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="nisn"
              name="nisn"
              value="{{ old('nisn', $siswa->nisn) }}"
              required
              placeholder="Contoh: 0071234567"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <!-- Kelas -->
          <div>
            <label for="kelas" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Kelas <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="kelas"
              name="kelas"
              value="{{ old('kelas', $siswa->kelas) }}"
              required
              placeholder="Contoh: XI TKJ 1 / XII RPL 2"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <!-- Jurusan -->
          <div class="md:col-span-2">
            <label for="jurusan" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Program Keahlian / Jurusan <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="jurusan"
              name="jurusan"
              value="{{ old('jurusan', $siswa->jurusan) }}"
              required
              placeholder="Contoh: Teknik Komputer dan Jaringan"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>
        </div>
      </div>

      <!-- Section 2: Akun Login Siswa -->
      <div>
        <h2 class="text-sm font-extrabold text-on-surface flex items-center gap-2 mb-4 pb-2 border-b border-border-hairline">
          <span class="material-symbols-outlined text-primary text-[18px]">lock</span>
          <span>2. Akun Akses Sistem</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Username -->
          <div>
            <label for="username" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Username Siswa <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="username"
              name="username"
              value="{{ old('username', $siswa->username) }}"
              required
              placeholder="Contoh: nathanhall"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Email (Opsional)
            </label>
            <input
              type="email"
              id="email"
              name="email"
              value="{{ old('email', $siswa->email) }}"
              placeholder="Contoh: nathan@sekolah.sch.id"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <!-- Password -->
          <div class="md:col-span-2">
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
        </div>
      </div>

      <!-- Section 3: Penempatan & Pembimbing -->
      <div>
        <h2 class="text-sm font-extrabold text-on-surface flex items-center gap-2 mb-4 pb-2 border-b border-border-hairline">
          <span class="material-symbols-outlined text-primary text-[18px]">apartment</span>
          <span>3. Penempatan Industri & Pembimbing</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Tempat PKL -->
          <div>
            <label for="tempat_pkl_id" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Tempat PKL Industri Mitra
            </label>
            <select
              id="tempat_pkl_id"
              name="tempat_pkl_id"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
              <option value="">-- Pilih Tempat Industri --</option>
              @foreach($tempatPklList as $tp)
                <option value="{{ $tp->id }}" {{ old('tempat_pkl_id', $siswa->tempat_pkl_id) == $tp->id ? 'selected' : '' }}>
                  {{ $tp->nama_perusahaan }} ({{ $tp->kota ?? 'Tanpa Kota' }})
                </option>
              @endforeach
            </select>
          </div>

          <!-- Guru Pembimbing -->
          <div>
            <label for="guru_id" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Guru Pembimbing Sekolah
            </label>
            <select
              id="guru_id"
              name="guru_id"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
              <option value="">-- Pilih Guru Pembimbing --</option>
              @foreach($guruList as $gr)
                <option value="{{ $gr->id }}" {{ old('guru_id', $siswa->guru_id) == $gr->id ? 'selected' : '' }}>
                  {{ $gr->name }} (NIP: {{ $gr->nip ?? '-' }})
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <!-- Actions Button -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-hairline">
        <a href="{{ route('admin.siswa.index') }}" class="px-5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-background text-text-secondary font-bold text-xs transition-colors">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">save</span>
          <span>{{ $isEdit ? 'Simpan Perubahan' : 'Daftarkan Siswa' }}</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
