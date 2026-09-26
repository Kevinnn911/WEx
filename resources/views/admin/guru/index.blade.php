<!-- File: resources/views/admin/guru/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Guru Pembimbing — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Header Page -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">Data Guru Pembimbing PKL</h1>
      <p class="text-xs text-text-secondary mt-0.5">
        Manajemen akun guru pembimbing yang bertugas mengawasi presensi dan aktivitas kerja siswa di industri.
      </p>
    </div>
    <div>
      <a href="{{ route('admin.guru.create') }}" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">person_add</span>
        <span>Tambah Guru Pembimbing</span>
      </a>
    </div>
  </div>

  <!-- Search Bar -->
  <div class="p-4 rounded-2xl bg-white border border-border-hairline shadow-xs">
    <form method="GET" action="{{ route('admin.guru.index') }}" class="flex flex-col md:flex-row items-center gap-3">
      <div class="relative flex-1 w-full">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] text-text-secondary">
          search
        </span>
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari berdasarkan nama, NIP, username, atau email guru..."
          class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>
      <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white font-bold text-xs hover:bg-[#007080] transition-colors shrink-0">
        Cari Data
      </button>
      @if(request('search'))
        <a href="{{ route('admin.guru.index') }}" class="px-4 py-2.5 rounded-xl border border-border-hairline text-text-secondary text-xs font-bold hover:bg-background transition-colors shrink-0">
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
            <th class="py-3.5 px-6">Identitas Guru</th>
            <th class="py-3.5 px-6">NIP</th>
            <th class="py-3.5 px-6">Kontak / Email</th>
            <th class="py-3.5 px-6 text-center">Siswa Bimbingan</th>
            <th class="py-3.5 px-6 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline">
          @forelse($guruList as $item)
            <tr class="hover:bg-[#FAFDFE] transition-colors">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm shrink-0">
                    {{ strtoupper(substr($item->name, 0, 1)) }}
                  </div>
                  <div>
                    <p class="font-extrabold text-on-surface text-sm">{{ $item->name }}</p>
                    <p class="text-[11px] text-text-secondary">Username: @<span>{{ $item->username }}</span></p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6 font-semibold text-on-surface">
                {{ $item->nip ?? '-' }}
              </td>
              <td class="py-4 px-6 font-semibold text-on-surface">
                {{ $item->email ?? '-' }}
              </td>
              <td class="py-4 px-6 text-center">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold {{ $item->siswa_bimbingan_count > 0 ? 'bg-primary-soft text-primary' : 'bg-background text-text-secondary' }}">
                  <span class="material-symbols-outlined text-[14px]">school</span>
                  <span>{{ $item->siswa_bimbingan_count }} Siswa</span>
                </span>
              </td>
              <td class="py-4 px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.guru.edit', $item->id) }}" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-background text-text-secondary hover:text-primary transition-colors" title="Edit Data">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                  </a>
                  <form action="{{ route('admin.guru.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru pembimbing ini? Relasi bimbingan siswa terkait akan dilepas.');">
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
              <td colspan="5" class="py-12 px-6 text-center text-text-secondary">
                <div class="flex flex-col items-center justify-center">
                  <span class="material-symbols-outlined text-[48px] text-text-secondary/40 mb-2">person_outline</span>
                  <p class="font-bold text-sm text-on-surface">Belum ada data guru pembimbing</p>
                  <p class="text-xs text-text-secondary mt-0.5">Silakan tambahkan guru pembimbing melalui tombol di atas.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($guruList->hasPages())
      <div class="p-4 border-t border-border-hairline">
        {{ $guruList->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
