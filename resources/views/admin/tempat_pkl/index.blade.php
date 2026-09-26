<!-- File: resources/views/admin/tempat_pkl/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Tempat PKL Industri — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Header Page -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">Tempat PKL Industri Mitra</h1>
      <p class="text-xs text-text-secondary mt-0.5">
        Daftar perusahaan dan instansi dunia usaha/dunia industri (DUDI) tempat siswa melaksanakan PKL.
      </p>
    </div>
    <div>
      <a href="{{ route('admin.tempat-pkl.create') }}" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">add_business</span>
        <span>Tambah Tempat PKL</span>
      </a>
    </div>
  </div>

  <!-- Search & Filter Bar -->
  <div class="p-4 rounded-2xl bg-white border border-border-hairline shadow-xs">
    <form method="GET" action="{{ route('admin.tempat-pkl.index') }}" class="flex flex-col md:flex-row items-center gap-3">
      <div class="relative flex-1 w-full">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] text-text-secondary">
          search
        </span>
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari nama perusahaan, bidang usaha, atau kota..."
          class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-bold text-xs hover:bg-[#007080] transition-colors shrink-0">
        Cari Data
      </button>
      @if(request('search'))
        <a href="{{ route('admin.tempat-pkl.index') }}" class="px-4 py-2.5 rounded-xl border border-border-hairline text-text-secondary text-xs font-bold hover:bg-background transition-colors shrink-0">
          Reset
        </a>
      @endif
    </form>
  </div>

  <!-- Table Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead>
          <tr class="border-b border-border-hairline bg-[#FAFDFE] text-text-secondary uppercase tracking-wider text-[11px] font-bold">
            <th class="py-3.5 px-6">Perusahaan / Mitra</th>
            <th class="py-3.5 px-6">Bidang Usaha</th>
            <th class="py-3.5 px-6">Kota / Alamat</th>
            <th class="py-3.5 px-6">Kontak</th>
            <th class="py-3.5 px-6 text-center">Siswa Aktif</th>
            <th class="py-3.5 px-6 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline">
          @forelse($tempatPklList as $item)
            <tr class="hover:bg-[#FAFDFE] transition-colors">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-primary-soft text-primary flex items-center justify-center font-bold shrink-0">
                    <span class="material-symbols-outlined text-[20px]">apartment</span>
                  </div>
                  <div>
                    <p class="font-extrabold text-on-surface text-sm">{{ $item->nama_perusahaan }}</p>
                    <p class="text-[11px] text-text-secondary">ID Mitra #{{ $item->id }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6 font-semibold text-on-surface">
                {{ $item->bidang ?? '-' }}
              </td>
              <td class="py-4 px-6 text-text-secondary">
                <p class="font-bold text-on-surface">{{ $item->kota ?? '-' }}</p>
                <p class="text-[11px] truncate max-w-xs">{{ $item->alamat ?? '-' }}</p>
              </td>
              <td class="py-4 px-6 font-semibold text-on-surface">
                {{ $item->kontak ?? '-' }}
              </td>
              <td class="py-4 px-6 text-center">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold {{ $item->siswa_count > 0 ? 'bg-mint-soft text-status-success' : 'bg-background text-text-secondary' }}">
                  <span class="material-symbols-outlined text-[14px]">group</span>
                  <span>{{ $item->siswa_count }} Siswa</span>
                </span>
              </td>
              <td class="py-4 px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.tempat-pkl.edit', $item->id) }}" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-background text-text-secondary hover:text-primary transition-colors" title="Edit Data">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                  </a>
                  <form action="{{ route('admin.tempat-pkl.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tempat PKL ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-red-50 text-text-secondary hover:text-status-error transition-colors" title="Hapus Data">
                      <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-12 px-6 text-center text-text-secondary">
                <div class="flex flex-col items-center justify-center">
                  <span class="material-symbols-outlined text-[48px] text-text-secondary/40 mb-2">apartment</span>
                  <p class="font-bold text-sm text-on-surface">Belum ada data tempat PKL</p>
                  <p class="text-xs text-text-secondary mt-0.5">Silakan tambahkan data industri mitra baru melalui tombol di atas.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($tempatPklList->hasPages())
      <div class="p-4 border-t border-border-hairline">
        {{ $tempatPklList->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
