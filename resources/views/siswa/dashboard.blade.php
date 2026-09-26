<!-- File: resources/views/siswa/dashboard.blade.php -->
@extends('layouts.siswa')

@section('title', 'Beranda Siswa - Sistem Monitoring PKL')

@section('content')
<div class="space-y-5">
  <!-- Profile & Sapaan Card -->
  <div class="flex items-center justify-between">
    <div>
      <p class="text-xs font-bold text-text-secondary uppercase tracking-wider">{{ $salam }}</p>
      <h1 class="text-xl font-black text-on-surface tracking-tight">{{ $user->name }}</h1>
      <p class="text-xs text-text-secondary mt-0.5">{{ $user->kelas ?? 'Siswa PKL' }} &bull; {{ $user->jurusan ?? 'SMK' }}</p>
    </div>
    <a href="{{ route('siswa.profil') }}" class="w-12 h-12 rounded-full bg-white border-2 border-primary/20 shadow-xs flex items-center justify-center text-primary font-extrabold text-base hover:border-primary transition-all">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </a>
  </div>

  <!-- PKL Day Progress Pill -->
  <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-white/80 border border-border-hairline shadow-xs">
    <div class="flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px] text-primary">calendar_month</span>
      <span class="text-xs font-bold text-on-surface">Hari ke-{{ $hariKe }}</span>
      <span class="text-xs text-text-secondary">dari {{ $totalHari }} hari periode PKL</span>
    </div>
    <span class="text-xs font-black text-primary">{{ round(($hariKe / max(1, $totalHari)) * 100) }}%</span>
  </div>

  <!-- Main Hero Card: Status Hari Ini -->
  @if(!$absensiHariIni)
    <!-- State: Belum Absen -->
    <div class="p-5 rounded-2xl bg-white border border-[#F3D58C] shadow-sm relative overflow-hidden">
      <div class="absolute -right-4 -bottom-4 w-28 h-28 rounded-full bg-yellow-soft/50 pointer-events-none"></div>
      <div class="flex items-center justify-between mb-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-soft text-status-warning">
          <span class="material-symbols-outlined text-[14px]">pending</span>
          Belum Absen Hari Ini
        </span>
        <span class="text-xs font-semibold text-text-secondary">
          {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
        </span>
      </div>

      <h2 class="text-base font-extrabold text-on-surface mb-1">Ambil Foto Selfie Presensi</h2>
      <p class="text-xs text-text-secondary leading-relaxed mb-4">
        Presensi wajib dilakukan 1 kali per hari melalui kamera browser.
      </p>

      <a
        href="{{ route('siswa.absensi') }}"
        class="w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-2"
      >
        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
        <span>Buka Kamera &amp; Absen Sekarang</span>
      </a>
    </div>
  @else
    <!-- State: Sudah Absen -->
    <div class="p-5 rounded-2xl bg-white border border-[#A4E3BE] shadow-sm relative overflow-hidden">
      <div class="absolute -right-4 -bottom-4 w-28 h-28 rounded-full bg-mint-soft/50 pointer-events-none"></div>
      <div class="flex items-center justify-between mb-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-mint-soft text-status-success">
          <span class="material-symbols-outlined text-[14px]">check_circle</span>
          Sudah Hadir Hari Ini
        </span>
        <span class="text-xs font-bold text-on-surface">
          {{ substr($absensiHariIni->jam, 0, 5) }} WIB
        </span>
      </div>

      <div class="mb-4">
        <h2 class="text-base font-extrabold text-on-surface">Presensi Telah Tercatat</h2>
        <p class="text-xs text-text-secondary mt-0.5">
          Foto selfie presensi Anda sudah tersimpan di sistem sekolah.
        </p>
      </div>

      @if(!$laporanHariIni)
        <!-- Prompt untuk Laporan Harian -->
        <div class="p-3.5 rounded-xl bg-yellow-soft/60 border border-[#F3D58C] mb-3">
          <div class="flex items-center gap-2 text-status-warning text-xs font-bold mb-1">
            <span class="material-symbols-outlined text-[16px]">edit_note</span>
            <span>Rencana Tugas Belum Dikirim</span>
          </div>
          <p class="text-[11px] text-text-secondary leading-snug">
            Tuliskan rencana pekerjaan yang akan Anda laksanakan di tempat PKL hari ini.
          </p>
        </div>

        <a
          href="{{ route('siswa.laporan') }}"
          class="w-full py-3 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">assignment</span>
          <span>Isi Rencana Tugas Harian</span>
        </a>
      @else
        <!-- Laporan Sudah Dikirim -->
        <div class="p-3.5 rounded-xl bg-mint-soft/60 border border-[#A4E3BE]">
          <div class="flex items-center justify-between text-xs font-bold text-status-success mb-1">
            <div class="flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[16px]">task_alt</span>
              <span>Laporan Harian Terkirim</span>
            </div>
            <a href="{{ route('siswa.laporan') }}" class="text-[11px] text-primary hover:underline">Lihat Detail</a>
          </div>
          <p class="text-[11px] text-on-surface line-clamp-2 italic">
            "{{ $laporanHariIni->rencana_tugas }}"
          </p>
        </div>
      @endif
    </div>
  @endif

  <!-- Tempat PKL & Pembimbing Card -->
  <div class="p-5 rounded-2xl bg-white border border-border-hairline shadow-xs space-y-3">
    <div class="flex items-center justify-between">
      <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider">Informasi Penempatan PKL</h3>
      <span class="material-symbols-outlined text-[18px] text-text-secondary">domain</span>
    </div>

    <div class="flex items-start gap-3 pt-1">
      <div class="w-10 h-10 rounded-xl bg-primary-soft text-primary flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[20px]">apartment</span>
      </div>
      <div class="min-w-0">
        <h4 class="text-sm font-extrabold text-on-surface truncate">
          {{ $user->tempatPkl ? $user->tempatPkl->nama_perusahaan : 'Belum Ditempatkan' }}
        </h4>
        <p class="text-xs text-text-secondary">
          {{ $user->tempatPkl ? $user->tempatPkl->bidang : 'Silakan hubungi koordinator PKL sekolah' }}
        </p>
      </div>
    </div>

    <div class="border-t border-border-hairline pt-3 flex items-center justify-between text-xs">
      <span class="text-text-secondary font-medium">Guru Pembimbing:</span>
      <span class="font-bold text-on-surface">
        {{ $user->guruPembimbing ? $user->guruPembimbing->name : 'Belum Ditugaskan' }}
      </span>
    </div>
  </div>

  <!-- Menu Aksi Cepat Grid -->
  <div>
    <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-3">Menu Aktivitas</h3>
    <div class="grid grid-cols-2 gap-3">
      <a href="{{ route('siswa.absensi') }}" class="p-4 rounded-xl bg-white border border-border-hairline hover:border-primary/40 shadow-xs flex flex-col justify-between transition-all group">
        <div class="w-9 h-9 rounded-lg bg-primary-soft text-primary flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
          <span class="material-symbols-outlined text-[20px]">photo_camera</span>
        </div>
        <div>
          <p class="text-xs font-bold text-on-surface">Absensi Harian</p>
          <p class="text-[11px] text-text-secondary mt-0.5">Foto Selfie Kamera</p>
        </div>
      </a>

      <a href="{{ route('siswa.laporan') }}" class="p-4 rounded-xl bg-white border border-border-hairline hover:border-primary/40 shadow-xs flex flex-col justify-between transition-all group">
        <div class="w-9 h-9 rounded-lg bg-mint-soft text-[#18794E] flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
          <span class="material-symbols-outlined text-[20px]">description</span>
        </div>
        <div>
          <p class="text-xs font-bold text-on-surface">Laporan Tugas</p>
          <p class="text-[11px] text-text-secondary mt-0.5">Rencana Kerja Hari Ini</p>
        </div>
      </a>

      <a href="{{ route('siswa.riwayat') }}" class="p-4 rounded-xl bg-white border border-border-hairline hover:border-primary/40 shadow-xs flex flex-col justify-between transition-all group">
        <div class="w-9 h-9 rounded-lg bg-yellow-soft text-[#A15C00] flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
          <span class="material-symbols-outlined text-[20px]">history</span>
        </div>
        <div>
          <p class="text-xs font-bold text-on-surface">Riwayat Presensi</p>
          <p class="text-[11px] text-text-secondary mt-0.5">Rekap Kehadiran</p>
        </div>
      </a>

      <a href="{{ route('siswa.profil') }}" class="p-4 rounded-xl bg-white border border-border-hairline hover:border-primary/40 shadow-xs flex flex-col justify-between transition-all group">
        <div class="w-9 h-9 rounded-lg bg-[#EDE7F6] text-[#5E35B1] flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
          <span class="material-symbols-outlined text-[20px]">badge</span>
        </div>
        <div>
          <p class="text-xs font-bold text-on-surface">Profil &amp; Akun</p>
          <p class="text-[11px] text-text-secondary mt-0.5">Data Diri &amp; PKL</p>
        </div>
      </a>
    </div>
  </div>
</div>
@endsection
