<!-- File: resources/views/admin/periode/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manajemen Periode PKL — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Header Page -->
  <div>
    <h1 class="text-2xl font-black text-on-surface tracking-tight">Manajemen Periode PKL</h1>
    <p class="text-xs text-text-secondary mt-0.5">
      Pengaturan semester dan rentang tanggal pelaksanaan Praktik Kerja Lapangan.
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Periode (1 Kolom) -->
    <div class="lg:col-span-1">
      <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6">
        <h2 class="text-sm font-extrabold text-on-surface flex items-center gap-2 mb-4 pb-2 border-b border-border-hairline">
          <span class="material-symbols-outlined text-primary text-[18px]">add_circle</span>
          <span>Tambah Periode Baru</span>
        </h2>
        <form action="{{ route('admin.periode.store') }}" method="POST" class="space-y-4">
          @csrf

          <div>
            <label for="nama_periode" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Nama Periode <span class="text-status-error">*</span>
            </label>
            <input
              type="text"
              id="nama_periode"
              name="nama_periode"
              required
              placeholder="Contoh: PKL Semester Ganjil 2026/2027"
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <div>
            <label for="tanggal_mulai" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Tanggal Mulai <span class="text-status-error">*</span>
            </label>
            <input
              type="date"
              id="tanggal_mulai"
              name="tanggal_mulai"
              required
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <div>
            <label for="tanggal_selesai" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
              Tanggal Selesai <span class="text-status-error">*</span>
            </label>
            <input
              type="date"
              id="tanggal_selesai"
              name="tanggal_selesai"
              required
              class="w-full px-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
          </div>

          <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="is_aktif" value="1" class="rounded border-border-hairline text-primary focus:ring-primary">
              <span class="text-xs font-bold text-on-surface">Jadikan sebagai periode aktif</span>
            </label>
            <p class="text-[11px] text-text-secondary mt-1 ml-5">
              Periode aktif akan menjadi acuan tanggal bagi siswa dan guru dalam semester berjalan.
            </p>
          </div>

          <div class="pt-3">
            <button type="submit" class="w-full py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-[18px]">save</span>
              <span>Simpan Periode</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabel Daftar Periode (2 Kolom) -->
    <div class="lg:col-span-2">
      <div class="rounded-2xl bg-white border border-border-hairline shadow-xs overflow-hidden">
        <div class="p-5 border-b border-border-hairline flex items-center justify-between">
          <h2 class="text-sm font-extrabold text-on-surface flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[18px]">calendar_month</span>
            <span>Daftar Seluruh Periode PKL</span>
          </h2>
          <span class="text-xs text-text-secondary font-semibold">{{ count($periodeList) }} Periode Terdata</span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-border-hairline bg-[#FAFDFE] text-text-secondary uppercase tracking-wider text-[11px] font-bold">
                <th class="py-3.5 px-6">Nama Periode</th>
                <th class="py-3.5 px-6">Rentang Waktu</th>
                <th class="py-3.5 px-6 text-center">Status</th>
                <th class="py-3.5 px-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-border-hairline">
              @forelse($periodeList as $item)
                <tr class="hover:bg-[#FAFDFE] transition-colors">
                  <td class="py-4 px-6 font-extrabold text-on-surface text-sm">
                    {{ $item->nama_periode }}
                  </td>
                  <td class="py-4 px-6 text-text-secondary">
                    <p class="font-bold text-on-surface">
                      {{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('D MMM Y') }} &mdash; {{ \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('D MMM Y') }}
                    </p>
                    <p class="text-[11px]">
                      Durasi: {{ \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai)) + 1 }} Hari
                    </p>
                  </td>
                  <td class="py-4 px-6 text-center">
                    @if($item->is_aktif)
                      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-mint-soft text-status-success">
                        <span class="material-symbols-outlined text-[15px]">check_circle</span>
                        <span>AKTIF</span>
                      </span>
                    @else
                      <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-background text-text-secondary">
                        <span>Nonaktif</span>
                      </span>
                    @endif
                  </td>
                  <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                      @if(!$item->is_aktif)
                        <form action="{{ route('admin.periode.set-aktif', $item->id) }}" method="POST">
                          @csrf
                          <button type="submit" class="px-3 py-1.5 rounded-lg border border-border-hairline bg-white hover:bg-primary-soft text-primary font-bold text-xs transition-colors flex items-center gap-1 shadow-xs">
                            <span class="material-symbols-outlined text-[14px]">toggle_on</span>
                            <span>Aktifkan</span>
                          </button>
                        </form>
                      @endif
                      <form action="{{ route('admin.periode.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data periode PKL ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg border border-border-hairline bg-white hover:bg-red-50 text-text-secondary hover:text-status-error transition-colors" title="Hapus Periode">
                          <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="py-12 px-6 text-center text-text-secondary">
                    <p class="font-bold text-sm text-on-surface">Belum ada periode PKL terdaftar</p>
                    <p class="text-xs text-text-secondary mt-0.5">Tambahkan periode melalui form di samping kiri.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
