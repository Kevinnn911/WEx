<!-- File: resources/views/siswa/riwayat.blade.php -->
@extends('layouts.siswa')

@section('title', 'Riwayat Aktivitas — Sistem Monitoring PKL')

@section('content')
<div class="space-y-5">
  <!-- Top App Bar Navigation -->
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <a href="{{ route('siswa.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
      </a>
      <div>
        <h1 class="text-lg font-black text-on-surface leading-tight">Riwayat Aktivitas</h1>
        <p class="text-xs text-text-secondary">Rekap Kehadiran & Laporan Harian</p>
      </div>
    </div>
    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-primary-soft text-primary">
      <span class="material-symbols-outlined text-[14px]">event_available</span>
      <span>{{ $riwayatList->count() }} Hari</span>
    </span>
  </div>

  <!-- Riwayat List -->
  @if($riwayatList->isEmpty())
    <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-8 text-center space-y-2">
      <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center">
        <span class="material-symbols-outlined text-[28px]">history</span>
      </div>
      <h3 class="text-sm font-bold text-on-surface">Belum Ada Riwayat</h3>
      <p class="text-xs text-text-secondary max-w-[240px] mx-auto">
        Data presensi dan laporan harian yang Anda kirim akan tercatat otomatis di halaman ini.
      </p>
    </div>
  @else
    <div class="space-y-3">
      @foreach($riwayatList as $item)
        <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-4 space-y-2.5 transition-all hover:border-primary/30">
          <!-- Card Header: Tanggal & Status -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-lg bg-mint-soft text-status-success flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px]">check</span>
              </div>
              <div>
                <h3 class="text-xs font-black text-on-surface">
                  {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd, D MMMM Y') }}
                </h3>
                <span class="text-[11px] text-text-secondary flex items-center gap-1">
                  <span class="material-symbols-outlined text-[13px]">schedule</span>
                  <span>{{ substr($item->jam, 0, 5) }} WIB</span>
                </span>
              </div>
            </div>

            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-mint-soft text-status-success">
              <span class="material-symbols-outlined text-[13px]">check_circle</span>
              <span>Hadir</span>
            </span>
          </div>

          <!-- Laporan Harian Body -->
          <div class="pt-2 border-t border-border-hairline/80">
            @if($item->laporan)
              <div class="p-3 rounded-xl bg-[#FAFDFE] border border-border-hairline text-xs">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-[10px] font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">assignment</span>
                    <span>Laporan Rencana Tugas:</span>
                  </span>
                  <span class="text-[10px] font-bold text-status-success">Terkirim</span>
                </div>
                <p class="text-on-surface leading-relaxed">
                  {{ $item->laporan->rencana_tugas }}
                </p>
                @if($item->laporan->catatan)
                  <p class="text-text-secondary italic text-[11px] mt-1 pt-1 border-t border-border-hairline">
                    Catatan: {{ $item->laporan->catatan }}
                  </p>
                @endif
              </div>
            @else
              <div class="p-2.5 rounded-xl bg-yellow-soft/50 border border-[#F3D58C] text-[11px] text-status-warning flex items-center justify-between">
                <span class="flex items-center gap-1">
                  <span class="material-symbols-outlined text-[14px]">warning</span>
                  <span>Laporan belum diisi</span>
                </span>
                @if($item->tanggal->toDateString() === \Carbon\Carbon::today()->toDateString())
                  <a href="{{ route('siswa.laporan') }}" class="font-bold text-primary hover:underline">
                    Isi Sekarang
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
