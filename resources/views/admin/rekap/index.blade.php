<!-- File: resources/views/admin/rekap/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Rekapitulasi Presensi & Jurnal PKL — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Header Page & Action Buttons -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">Rekapitulasi Presensi &amp; Jurnal PKL</h1>
      <p class="text-xs text-text-secondary mt-0.5">
        Laporan rekap kehadiran siswa dan catatan jurnal harian untuk evaluasi dan arsip kurikulum sekolah.
      </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <!-- Ekspor Excel (.xls) — Format Utama & Rapi seperti Template Siswa -->
      <a
        href="{{ route('admin.rekap.export-excel', request()->query()) }}"
        class="px-4 py-2.5 rounded-xl border border-primary/30 bg-primary-soft hover:bg-primary/20 text-primary font-extrabold text-xs shadow-xs transition-colors flex items-center gap-2"
        title="Unduh laporan lengkap format Microsoft Excel (.xls) dengan styling tabel dan kolom rapi"
      >
        <span class="material-symbols-outlined text-[18px]">table_view</span>
        <span>Ekspor Excel (.xls)</span>
      </a>

      <!-- Ekspor CSV (.csv) — Kompatibel Excel Regional Indonesia -->
      <a
        href="{{ route('admin.rekap.export-csv', request()->query()) }}"
        class="px-3.5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-gray-50 text-text-secondary font-bold text-xs shadow-xs transition-colors flex items-center gap-2"
        title="Unduh format spreadsheet CSV (Pemisah titik-koma ';' kompatibel Excel)"
      >
        <span class="material-symbols-outlined text-[18px]">download</span>
        <span>Ekspor CSV</span>
      </a>

      <!-- Cetak / Print Dokumen A4 Resmi -->
      <a
        href="{{ route('admin.rekap.cetak', request()->query()) }}"
        target="_blank"
        class="px-4 py-2.5 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs shadow-xs transition-colors flex items-center gap-2"
        title="Buka tampilan cetak A4 resmi"
      >
        <span class="material-symbols-outlined text-[18px]">print</span>
        <span>Cetak Laporan</span>
      </a>
    </div>
  </div>

  <!-- KPI Metrics Cards Row -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="p-5 rounded-2xl bg-white border border-border-hairline shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-text-secondary uppercase tracking-wider">Total Rekap Presensi</p>
        <p class="text-3xl font-black text-on-surface mt-1">{{ $totalPresensi }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Dalam rentang tanggal terpilih</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-primary-soft text-primary flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">fact_check</span>
      </div>
    </div>

    <div class="p-5 rounded-2xl bg-white border border-[#A4E3BE] shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-status-success uppercase tracking-wider">Total Kehadiran</p>
        <p class="text-3xl font-black text-status-success mt-1">{{ $totalHadir }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Status hadir tepat waktu</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-mint-soft text-status-success flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">check_circle</span>
      </div>
    </div>

    <div class="p-5 rounded-2xl bg-white border border-[#F3D58C] shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-status-warning uppercase tracking-wider">Izin &amp; Sakit</p>
        <p class="text-3xl font-black text-status-warning mt-1">{{ $totalIzinSakit }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Ketidakhadiran berizin</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-yellow-soft text-status-warning flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">notification_important</span>
      </div>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="p-5 rounded-2xl bg-white border border-border-hairline shadow-xs">
    <form method="GET" action="{{ route('admin.rekap.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
      <!-- Tanggal Mulai -->
      <div>
        <label for="start_date" class="block text-[11px] font-bold text-text-secondary uppercase tracking-wider mb-1.5">
          Dari Tanggal
        </label>
        <input
          type="date"
          id="start_date"
          name="start_date"
          value="{{ $startDate }}"
          class="w-full py-2 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Tanggal Selesai -->
      <div>
        <label for="end_date" class="block text-[11px] font-bold text-text-secondary uppercase tracking-wider mb-1.5">
          Sampai Tanggal
        </label>
        <input
          type="date"
          id="end_date"
          name="end_date"
          value="{{ $endDate }}"
          class="w-full py-2 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Filter Kelas -->
      <div>
        <label for="kelas" class="block text-[11px] font-bold text-text-secondary uppercase tracking-wider mb-1.5">
          Kelas
        </label>
        <select
          id="kelas"
          name="kelas"
          class="w-full py-2 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Semua Kelas</option>
          @foreach($daftarKelas as $kls)
            <option value="{{ $kls }}" {{ request('kelas') === $kls ? 'selected' : '' }}>
              {{ $kls }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Filter Tempat PKL -->
      <div>
        <label for="tempat_pkl_id" class="block text-[11px] font-bold text-text-secondary uppercase tracking-wider mb-1.5">
          Tempat PKL
        </label>
        <select
          id="tempat_pkl_id"
          name="tempat_pkl_id"
          class="w-full py-2 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Semua Tempat</option>
          @foreach($tempatPklList as $tp)
            <option value="{{ $tp->id }}" {{ request('tempat_pkl_id') == $tp->id ? 'selected' : '' }}>
              {{ $tp->nama_perusahaan }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Tombol Filter -->
      <div class="flex items-center gap-2">
        <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-primary hover:bg-[#007080] text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center gap-1">
          <span class="material-symbols-outlined text-[16px]">filter_list</span>
          <span>Terapkan</span>
        </button>
        <a href="{{ route('admin.rekap.index') }}" class="p-2 rounded-xl border border-border-hairline bg-white hover:bg-background text-text-secondary text-xs font-bold transition-colors" title="Reset Filter">
          <span class="material-symbols-outlined text-[18px]">restart_alt</span>
        </a>
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead>
          <tr class="border-b border-border-hairline bg-[#FAFDFE] text-text-secondary uppercase tracking-wider text-[11px] font-bold">
            <th class="py-3.5 px-6">Waktu Presensi</th>
            <th class="py-3.5 px-6">Siswa Peserta</th>
            <th class="py-3.5 px-6">Tempat &amp; Pembimbing</th>
            <th class="py-3.5 px-6 text-center">Status</th>
            <th class="py-3.5 px-6">Bukti Foto Presensi</th>
            <th class="py-3.5 px-6">Rencana Tugas / Jurnal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline">
          @forelse($absensiList as $item)
            <tr class="hover:bg-[#FAFDFE] transition-colors">
              <!-- Waktu -->
              <td class="py-4 px-6 text-text-secondary shrink-0">
                <p class="font-extrabold text-on-surface text-sm">
                  {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMM Y') }}
                </p>
                <p class="text-[11px] font-semibold flex items-center gap-1 mt-0.5">
                  <span class="material-symbols-outlined text-[14px]">schedule</span>
                  <span>{{ $item->jam ?? '-' }} WIB</span>
                </p>
              </td>

              <!-- Siswa -->
              <td class="py-4 px-6">
                <p class="font-extrabold text-on-surface text-sm">{{ $item->siswa->name ?? '-' }}</p>
                <p class="text-[11px] text-text-secondary">NISN: {{ $item->siswa->nisn ?? '-' }} &bull; {{ $item->siswa->kelas ?? '-' }}</p>
              </td>

              <!-- Tempat & Guru -->
              <td class="py-4 px-6">
                <p class="font-bold text-on-surface">{{ $item->siswa->tempatPkl->nama_perusahaan ?? '-' }}</p>
                <p class="text-[11px] text-text-secondary">Pembimbing: {{ $item->siswa->guruPembimbing->name ?? '-' }}</p>
              </td>

              <!-- Status -->
              <td class="py-4 px-6 text-center">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black {{ $item->status === 'hadir' ? 'bg-mint-soft text-status-success' : 'bg-yellow-soft text-status-warning' }}">
                  <span class="material-symbols-outlined text-[14px]">{{ $item->status === 'hadir' ? 'check_circle' : 'info' }}</span>
                  <span>{{ strtoupper($item->status ?? 'hadir') }}</span>
                </span>
              </td>

              <!-- Bukti Foto & TTD -->
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  @if($item->foto_url)
                    <a href="{{ $item->foto_url }}" target="_blank" class="w-10 h-10 rounded-lg overflow-hidden border border-border-hairline bg-background shrink-0 hover:opacity-80 transition-opacity" title="Lihat Selfie">
                      <img src="{{ $item->foto_url }}" alt="Foto Selfie" class="w-full h-full object-cover">
                    </a>
                  @else
                    <span class="text-[11px] text-text-secondary">-</span>
                  @endif

                  @if($item->ttd_url)
                    <a href="{{ $item->ttd_url }}" target="_blank" class="w-10 h-10 rounded-lg overflow-hidden border border-border-hairline bg-white p-1 shrink-0 hover:opacity-80 transition-opacity" title="Lihat TTD">
                      <img src="{{ $item->ttd_url }}" alt="Tanda Tangan" class="w-full h-full object-contain">
                    </a>
                  @endif
                </div>
              </td>

              <!-- Rencana Tugas -->
              <td class="py-4 px-6 max-w-xs">
                @if($item->laporan)
                  <p class="font-semibold text-on-surface text-xs line-clamp-2">{{ $item->laporan->rencana_tugas }}</p>
                  @if($item->laporan->catatan)
                    <p class="text-[11px] text-text-secondary mt-1 truncate">Catatan: {{ $item->laporan->catatan }}</p>
                  @endif
                @else
                  <span class="text-[11px] font-semibold text-text-secondary italic">Belum mengisi laporan harian</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-12 px-6 text-center text-text-secondary">
                <div class="flex flex-col items-center justify-center">
                  <span class="material-symbols-outlined text-[48px] text-text-secondary/40 mb-2">assessment</span>
                  <p class="font-bold text-sm text-on-surface">Tidak ada data rekap presensi</p>
                  <p class="text-xs text-text-secondary mt-0.5">Silakan pilih rentang tanggal atau kriteria filter yang berbeda.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($absensiList->hasPages())
      <div class="p-4 border-t border-border-hairline">
        {{ $absensiList->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
