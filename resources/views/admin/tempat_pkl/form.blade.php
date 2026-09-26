<!-- File: resources/views/admin/tempat_pkl/form.blade.php -->
@extends('layouts.admin')

@section('title', ($isEdit ? 'Edit' : 'Tambah') . ' Tempat PKL Industri — Sekolah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
  <!-- Back Button & Page Title -->
  <div class="flex items-center gap-3">
    <a href="{{ route('admin.tempat-pkl.index') }}" class="p-2 rounded-xl bg-white border border-border-hairline text-text-secondary hover:text-on-surface hover:bg-background transition-colors">
      <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">{{ $isEdit ? 'Edit Tempat PKL' : 'Tambah Tempat PKL Baru' }}</h1>
      <p class="text-xs text-text-secondary mt-0.5">Lengkapi formulir informasi perusahaan atau industri mitra.</p>
    </div>
  </div>

  <!-- Form Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 md:p-8">
    <form action="{{ $isEdit ? route('admin.tempat-pkl.update', $tempatPkl->id) : route('admin.tempat-pkl.store') }}" method="POST" class="space-y-5">
      @csrf
      @if($isEdit)
        @method('PUT')
      @endif

      <!-- Nama Perusahaan -->
      <div>
        <label for="nama_perusahaan" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          Nama Perusahaan / Industri <span class="text-status-error">*</span>
        </label>
        <input
          type="text"
          id="nama_perusahaan"
          name="nama_perusahaan"
          value="{{ old('nama_perusahaan', $tempatPkl->nama_perusahaan) }}"
          required
          placeholder="Contoh: PT Telkom Indonesia"
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Bidang Usaha -->
      <div>
        <label for="bidang" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          Bidang Usaha / Industri
        </label>
        <input
          type="text"
          id="bidang"
          name="bidang"
          value="{{ old('bidang', $tempatPkl->bidang) }}"
          placeholder="Contoh: Telekomunikasi & Jaringan / Software Engineering"
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Kota & Kontak Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="kota" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
            Kota / Kabupaten
          </label>
          <input
            type="text"
            id="kota"
            name="kota"
            value="{{ old('kota', $tempatPkl->kota) }}"
            placeholder="Contoh: Bandung"
            class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
        </div>

        <div>
          <label for="kontak" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
            Nomor Kontak / Telepon / PIC
          </label>
          <input
            type="text"
            id="kontak"
            name="kontak"
            value="{{ old('kontak', $tempatPkl->kontak) }}"
            placeholder="Contoh: 022-4521510 / Bpk. Rudi"
            class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
        </div>
      </div>

      <!-- Alamat Lengkap -->
      <div>
        <label for="alamat" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
          Alamat Kantor / Lokasi PKL
        </label>
        <textarea
          id="alamat"
          name="alamat"
          rows="3"
          placeholder="Contoh: Jl. Japati No. 1, Sadang Serang, Kecamatan Coblong, Kota Bandung"
          class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >{{ old('alamat', $tempatPkl->alamat) }}</textarea>
      </div>

      <!-- Actions Button -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t border-border-hairline">
        <a href="{{ route('admin.tempat-pkl.index') }}" class="px-5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-background text-text-secondary font-bold text-xs transition-colors">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
          <span class="material-symbols-outlined text-[18px]">save</span>
          <span>{{ $isEdit ? 'Simpan Perubahan' : 'Tambahkan Tempat PKL' }}</span>
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
