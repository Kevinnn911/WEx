<!-- File: resources/views/admin/siswa/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Data Siswa & Penempatan PKL — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Header Page -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">Data Siswa & Penempatan PKL</h1>
      <p class="text-xs text-text-secondary mt-0.5">
        Manajemen data siswa peserta PKL, penempatan industri mitra, serta alokasi guru pembimbing.
      </p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5">
      <a href="{{ route('admin.siswa.template', ['format' => 'excel']) }}" class="px-3.5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-gray-50 text-text-secondary font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px] text-primary">table_view</span>
        <span>Unduh Template Excel</span>
      </a>
      <a href="{{ route('admin.siswa.import') }}" class="px-3.5 py-2.5 rounded-xl border border-primary/30 bg-primary-soft hover:bg-primary/20 text-primary font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">upload_file</span>
        <span>Import Akun (Bulk)</span>
      </a>
      <a href="{{ route('admin.siswa.create') }}" class="px-4 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">person_add</span>
        <span>Tambah Siswa Baru</span>
      </a>
    </div>
  </div>

  <!-- Search & Filter Bar -->
  <div class="p-4 rounded-2xl bg-white border border-border-hairline shadow-xs">
    <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex flex-col md:flex-row items-center gap-3">
      <!-- Search Input -->
      <div class="relative flex-1 w-full">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] text-text-secondary">
          search
        </span>
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari berdasarkan nama, NISN, username, atau kelas..."
          class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Filter Tempat PKL -->
      <div class="w-full md:w-56">
        <select
          name="tempat_pkl_id"
          class="w-full py-2.5 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Semua Tempat PKL</option>
          @foreach($tempatPklList as $tp)
            <option value="{{ $tp->id }}" {{ request('tempat_pkl_id') == $tp->id ? 'selected' : '' }}>
              {{ $tp->nama_perusahaan }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filter Guru Pembimbing -->
      <div class="w-full md:w-56">
        <select
          name="guru_id"
          class="w-full py-2.5 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Semua Guru Pembimbing</option>
          @foreach($guruList as $gr)
            <option value="{{ $gr->id }}" {{ request('guru_id') == $gr->id ? 'selected' : '' }}>
              {{ $gr->name }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center gap-2 w-full md:w-auto">
        <button type="submit" class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-primary text-white font-bold text-xs hover:bg-[#007080] transition-colors shrink-0">
          Filter
        </button>
        @if(request('search') || request('tempat_pkl_id') || request('guru_id'))
          <a href="{{ route('admin.siswa.index') }}" class="w-full md:w-auto px-4 py-2.5 rounded-xl border border-border-hairline text-text-secondary text-xs font-bold hover:bg-background transition-colors shrink-0 text-center">
            Reset
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead>
          <tr class="border-b border-border-hairline bg-[#FAFDFE] text-text-secondary uppercase tracking-wider text-[11px] font-bold">
            <th class="py-3.5 px-6">Identitas Siswa</th>
            <th class="py-3.5 px-6">Kelas & Jurusan</th>
            <th class="py-3.5 px-6">Tempat PKL Mitra</th>
            <th class="py-3.5 px-6">Guru Pembimbing</th>
            <th class="py-3.5 px-6 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline">
          @forelse($siswaList as $item)
            <tr class="hover:bg-[#FAFDFE] transition-colors">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm shrink-0">
                    {{ strtoupper(substr($item->name, 0, 1)) }}
                  </div>
                  <div>
                    <p class="font-extrabold text-on-surface text-sm">{{ $item->name }}</p>
                    <p class="text-[11px] text-text-secondary">NISN: {{ $item->nisn ?? '-' }} &bull; @<span>{{ $item->username }}</span></p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span class="inline-block px-2.5 py-1 rounded-md bg-[#FAFDFE] border border-border-hairline font-bold text-on-surface text-[11px]">
                  {{ $item->kelas ?? '-' }}
                </span>
                <p class="text-[11px] text-text-secondary mt-1 max-w-[200px] truncate">{{ $item->jurusan ?? '-' }}</p>
              </td>
              <td class="py-4 px-6">
                @if($item->tempatPkl)
                  <p class="font-bold text-on-surface">{{ $item->tempatPkl->nama_perusahaan }}</p>
                  <p class="text-[11px] text-text-secondary">{{ $item->tempatPkl->kota ?? 'Lokasi tidak diset' }}</p>
                @else
                  <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-status-warning bg-yellow-soft px-2.5 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[13px]">warning</span>
                    <span>Belum Ditempatkan</span>
                  </span>
                @endif
              </td>
              <td class="py-4 px-6">
                @if($item->guruPembimbing)
                  <p class="font-bold text-on-surface">{{ $item->guruPembimbing->name }}</p>
                  <p class="text-[11px] text-text-secondary">NIP: {{ $item->guruPembimbing->nip ?? '-' }}</p>
                @else
                  <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-text-secondary bg-background px-2.5 py-0.5 rounded-full">
                    <span>Belum Ditugaskan</span>
                  </span>
                @endif
              </td>
              <td class="py-4 px-6 text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="{{ route('admin.siswa.detail', $item->id) }}" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-background text-text-secondary hover:text-primary transition-colors" title="Lihat Monitoring Aktivitas">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                  </a>
                  <a href="{{ route('admin.siswa.edit', $item->id) }}" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-background text-text-secondary hover:text-primary transition-colors" title="Edit Data & Penempatan">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                  </a>
                  <form action="{{ route('admin.siswa.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun siswa ini? Seluruh riwayat presensi dan jurnal harian siswa ini juga akan terhapus.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg border border-border-hairline bg-white hover:bg-red-50 text-text-secondary hover:text-status-error transition-colors" title="Hapus Siswa">
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
                  <span class="material-symbols-outlined text-[48px] text-text-secondary/40 mb-2">groups</span>
                  <p class="font-bold text-sm text-on-surface">Data siswa tidak ditemukan</p>
                  <p class="text-xs text-text-secondary mt-0.5">Silakan sesuaikan kriteria pencarian atau tambahkan siswa baru.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($siswaList->hasPages())
      <div class="p-4 border-t border-border-hairline">
        {{ $siswaList->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
