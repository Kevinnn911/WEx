<!-- File: resources/views/admin/detail_siswa.blade.php -->
@extends('layouts.admin')

@section('title', 'Detail Siswa PKL - ' . $siswa->name)

@section('content')
<div class="space-y-6 max-w-5xl">
  <!-- Top Navigation & Back Link -->
  <div class="flex items-center gap-3">
    <a href="{{ route('admin.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
      <span class="material-symbols-outlined text-[20px]">arrow_back</span>
    </a>
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">{{ $siswa->name }}</h1>
      <p class="text-xs text-text-secondary">Detail Peninjauan Bukti Presensi & Laporan Siswa</p>
    </div>
  </div>

  <!-- Siswa Identity Card -->
  <div class="p-6 rounded-2xl bg-white border border-border-hairline shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
    <div class="flex items-center gap-4">
      <div class="w-16 h-16 rounded-2xl bg-primary-soft text-primary font-black text-2xl flex items-center justify-center border-2 border-primary/20 shrink-0">
        {{ strtoupper(substr($siswa->name, 0, 1)) }}
      </div>
      <div>
        <h2 class="text-lg font-black text-on-surface">{{ $siswa->name }}</h2>
        <p class="text-xs text-text-secondary">NISN: {{ $siswa->nisn ?? '-' }} &bull; Kelas: {{ $siswa->kelas ?? '-' }}</p>
        <p class="text-xs text-text-secondary mt-0.5">{{ $siswa->jurusan ?? '-' }}</p>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-xs border-t md:border-t-0 md:border-l border-border-hairline pt-4 md:pt-0 md:pl-6 w-full md:w-auto">
      <div>
        <p class="text-text-secondary font-medium">Tempat PKL:</p>
        <p class="font-bold text-on-surface">{{ $siswa->tempatPkl?->nama_perusahaan ?? '-' }}</p>
        <p class="text-[11px] text-text-secondary">{{ $siswa->tempatPkl?->kota ?? '' }}</p>
      </div>
      <div>
        <p class="text-text-secondary font-medium">Guru Pembimbing:</p>
        <p class="font-bold text-on-surface">{{ $siswa->guruPembimbing?->name ?? '-' }}</p>
      </div>
    </div>
  </div>

  <!-- Peninjauan Aktivitas Hari Ini -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 space-y-5">
    <div class="flex items-center justify-between pb-3 border-b border-border-hairline">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px] text-primary">verified</span>
        <h2 class="text-sm font-black text-on-surface uppercase tracking-wider">
          Aktivitas Hari Ini ({{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }})
        </h2>
      </div>
      @if($absensiHariIni)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-mint-soft text-status-success">
          <span class="material-symbols-outlined text-[14px]">check_circle</span>
          Hadir pada {{ substr($absensiHariIni->jam, 0, 5) }} WIB
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-soft text-status-warning">
          <span class="material-symbols-outlined text-[14px]">schedule</span>
          Belum Hadir Hari Ini
        </span>
      @endif
    </div>

    @if($absensiHariIni)
      @if($absensiHariIni->ttd_url)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Foto Selfie Wajah Bukti -->
          <div class="p-4 rounded-xl border border-border-hairline bg-[#FAFDFE]">
            <h3 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[16px] text-primary">face</span>
              <span>Bukti Foto Selfie Wajah</span>
            </h3>
            <div class="w-full aspect-4/3 rounded-xl overflow-hidden bg-gray-100 border border-border-hairline flex items-center justify-center">
              @if($absensiHariIni->foto_url)
                <img src="{{ $absensiHariIni->foto_url }}" alt="Selfie Wajah Siswa" class="w-full h-full object-cover">
              @else
                <div class="text-center p-4 text-text-secondary">
                  <span class="material-symbols-outlined text-[40px] text-primary mb-1">account_circle</span>
                  <p class="text-xs font-semibold">Tersimpan di Penyimpanan Sistem</p>
                </div>
              @endif
            </div>
            <p class="text-[11px] text-text-secondary mt-2 flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">access_time</span>
              <span>Terekam kamera pada: {{ substr($absensiHariIni->jam, 0, 5) }} WIB</span>
            </p>
          </div>

          <!-- Tanda Tangan Digital Bukti -->
          <div class="p-4 rounded-xl border border-border-hairline bg-[#FAFDFE] flex flex-col justify-between">
            <div>
              <h3 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-primary">draw</span>
                <span>Bukti Tanda Tangan Digital</span>
              </h3>
              <div class="w-full aspect-4/3 rounded-xl overflow-hidden bg-white border border-border-hairline p-4 flex items-center justify-center">
                <img src="{{ $absensiHariIni->ttd_url }}" alt="Tanda Tangan Siswa" class="w-full h-full object-contain">
              </div>
            </div>
            <p class="text-[11px] text-text-secondary mt-2">
              Status tanda tangan: <strong class="text-status-success">Sah & Terekam</strong>
            </p>
          </div>
        </div>
      @else
        <div class="max-w-md">
          <div class="p-4 rounded-xl border border-border-hairline bg-[#FAFDFE]">
            <h3 class="text-xs font-bold text-on-surface mb-2 flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[16px] text-primary">face</span>
              <span>Bukti Foto Selfie Wajah</span>
            </h3>
            <div class="w-full aspect-4/3 rounded-xl overflow-hidden bg-gray-100 border border-border-hairline flex items-center justify-center">
              @if($absensiHariIni->foto_url)
                <img src="{{ $absensiHariIni->foto_url }}" alt="Selfie Wajah Siswa" class="w-full h-full object-cover">
              @else
                <div class="text-center p-4 text-text-secondary">
                  <span class="material-symbols-outlined text-[40px] text-primary mb-1">account_circle</span>
                  <p class="text-xs font-semibold">Tersimpan di Penyimpanan Sistem</p>
                </div>
              @endif
            </div>
            <p class="text-[11px] text-text-secondary mt-2 flex items-center gap-1">
              <span class="material-symbols-outlined text-[13px]">access_time</span>
              <span>Terekam kamera pada: {{ substr($absensiHariIni->jam, 0, 5) }} WIB</span>
            </p>
          </div>
        </div>
      @endif

      <!-- Isi Laporan Harian Hari Ini -->
      <div class="pt-2">
        <h3 class="text-xs font-bold text-on-surface uppercase tracking-wider mb-2 flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px] text-primary">assignment</span>
          <span>Rencana Tugas yang Dilaporkan Hari Ini</span>
        </h3>
        @if($laporanHariIni)
          <div class="p-4 rounded-xl bg-[#FAFDFE] border border-border-hairline">
            <p class="text-xs text-on-surface leading-relaxed whitespace-pre-line">
              {{ $laporanHariIni->rencana_tugas }}
            </p>
            @if($laporanHariIni->catatan)
              <div class="mt-3 pt-2 border-t border-border-hairline text-xs text-text-secondary italic">
                Catatan: {{ $laporanHariIni->catatan }}
              </div>
            @endif
          </div>
        @else
          <div class="p-4 rounded-xl bg-yellow-soft/50 border border-[#F3D58C] text-xs text-status-warning flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">pending</span>
            <span>Siswa belum mengisi laporan rencana tugas hari ini.</span>
          </div>
        @endif
      </div>
    @else
      <div class="py-8 text-center text-text-secondary">
        <span class="material-symbols-outlined text-[40px] text-gray-400 mb-1">schedule</span>
        <p class="text-xs font-bold text-on-surface">Belum ada aktivitas presensi yang tercatat hari ini.</p>
        <p class="text-[11px] text-text-secondary mt-0.5">Siswa {{ $siswa->name }} belum melakukan absensi selfie wajah.</p>
      </div>
    @endif
  </div>

  <!-- Riwayat Aktivitas Lampau -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-sm font-black text-on-surface uppercase tracking-wider flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px] text-primary">history</span>
        <span>Riwayat Presensi & Laporan Lampau</span>
      </h2>
      <span class="text-xs text-text-secondary">Total: {{ $riwayatAktivitas->count() }} Hari</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#FAFDFE] border-b border-border-hairline text-[11px] font-bold text-text-secondary uppercase tracking-wider">
            <th class="py-3 px-4">Tanggal</th>
            <th class="py-3 px-4">Waktu</th>
            <th class="py-3 px-4">Status</th>
            <th class="py-3 px-4">Rencana Tugas / Laporan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline text-xs">
          @forelse($riwayatAktivitas as $act)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="py-3 px-4 font-bold text-on-surface">
                {{ \Carbon\Carbon::parse($act->tanggal)->isoFormat('D MMMM Y') }}
              </td>
              <td class="py-3 px-4 text-text-secondary">
                {{ substr($act->jam, 0, 5) }} WIB
              </td>
              <td class="py-3 px-4">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-mint-soft text-status-success">
                  <span class="material-symbols-outlined text-[12px]">check</span>
                  <span>{{ ucfirst($act->status) }}</span>
                </span>
              </td>
              <td class="py-3 px-4">
                @if($act->laporan)
                  <p class="text-on-surface line-clamp-2">{{ $act->laporan->rencana_tugas }}</p>
                @else
                  <span class="text-text-secondary italic">Tidak ada laporan</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="py-6 text-center text-text-secondary">
                Belum ada riwayat aktivitas lampau.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
