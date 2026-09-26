<!-- File: resources/views/siswa/laporan.blade.php -->
@extends('layouts.siswa')

@section('title', 'Laporan Harian — Sistem Monitoring PKL')

@section('content')
<div class="space-y-5">
  <!-- Top App Bar Navigation -->
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <a href="{{ route('siswa.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
      </a>
      <div>
        <h1 class="text-lg font-black text-on-surface leading-tight">Laporan Harian</h1>
        <p class="text-xs text-text-secondary">Rencana & Aktivitas Pekerjaan PKL</p>
      </div>
    </div>
    <span class="text-xs font-semibold text-text-secondary">
      {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}
    </span>
  </div>

  <!-- Context Card: Attendance Status Done -->
  <div class="p-3.5 rounded-xl bg-mint-soft border border-[#A4E3BE] flex items-center justify-between shadow-xs">
    <div class="flex items-center gap-2.5">
      <span class="material-symbols-outlined text-[20px] text-status-success">verified</span>
      <div>
        <p class="text-xs font-bold text-status-success">Absensi Hari Ini Lengkap</p>
        <p class="text-[11px] text-text-secondary">Tercatat pada {{ substr($absensiHariIni->jam, 0, 5) }} WIB</p>
      </div>
    </div>
    <a href="{{ route('siswa.absensi') }}" class="text-[11px] font-bold text-primary hover:underline">
      Tinjau Absen
    </a>
  </div>

  @if($laporanHariIni)
    <!-- State: Laporan Sudah Dikirim Hari Ini (Read-Only Confirmation) -->
    <div class="rounded-2xl bg-white border border-border-hairline shadow-sm p-6 space-y-4">
      <div class="flex items-center justify-between">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-mint-soft text-status-success">
          <span class="material-symbols-outlined text-[14px]">task_alt</span>
          Laporan Hari Ini Terkirim
        </span>
        <span class="text-xs font-semibold text-text-secondary">
          {{ \Carbon\Carbon::parse($laporanHariIni->created_at)->format('H:i') }} WIB
        </span>
      </div>

      <div>
        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
          Rencana Tugas yang Dilaporkan:
        </h2>
        <div class="p-4 rounded-xl bg-[#FAFDFE] border border-border-hairline text-sm text-on-surface leading-relaxed">
          {{ $laporanHariIni->rencana_tugas }}
        </div>
      </div>

      @if($laporanHariIni->catatan)
        <div>
          <h3 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
            Catatan Tambahan:
          </h3>
          <div class="p-3.5 rounded-xl bg-[#FAFDFE] border border-border-hairline text-xs text-text-secondary leading-relaxed">
            {{ $laporanHariIni->catatan }}
          </div>
        </div>
      @endif

      <div class="pt-2">
        <a
          href="{{ route('siswa.dashboard') }}"
          class="w-full py-3.5 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">home</span>
          <span>Kembali ke Beranda</span>
        </a>
      </div>
    </div>
  @else
    <!-- State: Form Pengisian Laporan Baru -->
    <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-5">
      <form action="{{ route('siswa.laporan.submit') }}" method="POST" class="space-y-4">
        @csrf

        <div>
          <label for="rencana_tugas" class="block text-sm font-extrabold text-on-surface mb-1">
            Apa yang akan Anda kerjakan hari ini?
          </label>
          <p class="text-xs text-text-secondary mb-2.5">
            Tuliskan rencana pekerjaan atau tugas secara singkat, jelas, dan spesifik.
          </p>
          <textarea
            id="rencana_tugas"
            name="rencana_tugas"
            rows="5"
            required
            minlength="5"
            placeholder="Contoh: Hari ini saya akan melakukan konfigurasi jaringan switch lantai 2 dan maintenance komputer bagian administrasi."
            class="w-full p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-sm text-on-surface placeholder:text-text-secondary/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
          >{{ old('rencana_tugas') }}</textarea>
        </div>

        <div>
          <label for="catatan" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-1">
            Catatan Tambahan (Opsional)
          </label>
          <textarea
            id="catatan"
            name="catatan"
            rows="2"
            placeholder="Tambahkan kendala atau catatan khusus jika ada."
            class="w-full p-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs text-on-surface placeholder:text-text-secondary/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
          >{{ old('catatan') }}</textarea>
        </div>

        <div class="pt-2">
          <button
            type="submit"
            class="w-full py-3.5 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-md transition-all flex items-center justify-center gap-2"
          >
            <span class="material-symbols-outlined text-[18px]">send</span>
            <span>Kirim Laporan</span>
          </button>
        </div>
      </form>
    </div>
  @endif
</div>
@endsection
