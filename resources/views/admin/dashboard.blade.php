<!-- File: resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard Monitoring PKL — Sekolah')

@section('content')
<div class="space-y-6">
  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-black text-on-surface tracking-tight">Monitoring Aktivitas Siswa PKL</h1>
      <p class="text-xs text-text-secondary mt-0.5">
        Pemantauan kehadiran selfie wajah dan laporan tugas harian siswa.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-border-hairline text-xs font-bold text-on-surface shadow-xs">
        <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
        <span>Hari ini: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
      </span>
    </div>
  </div>

  <!-- KPI Metrics Cards Row -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Total Siswa -->
    <div class="p-5 rounded-2xl bg-white border border-border-hairline shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-text-secondary uppercase tracking-wider">Total Siswa PKL</p>
        <p class="text-3xl font-black text-on-surface mt-1">{{ $totalSiswa }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Terdaftar dalam sistem</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-primary-soft text-primary flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">groups</span>
      </div>
    </div>

    <!-- Card 2: Hadir Hari Ini -->
    <div class="p-5 rounded-2xl bg-white border border-[#A4E3BE] shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-status-success uppercase tracking-wider">Hadir Hari Ini</p>
        <p class="text-3xl font-black text-status-success mt-1">{{ $totalHadir }}</p>
        <p class="text-[11px] text-text-secondary mt-1">{{ $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100) : 0 }}% tingkat kehadiran</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-mint-soft text-status-success flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">how_to_reg</span>
      </div>
    </div>

    <!-- Card 3: Belum Hadir -->
    <div class="p-5 rounded-2xl bg-white border border-[#F3D58C] shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-status-warning uppercase tracking-wider">Belum Presensi</p>
        <p class="text-3xl font-black text-status-warning mt-1">{{ $totalBelumHadir }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Menunggu absensi selfie</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-yellow-soft text-status-warning flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">person_off</span>
      </div>
    </div>

    <!-- Card 4: Laporan Terkirim -->
    <div class="p-5 rounded-2xl bg-white border border-border-hairline shadow-xs flex items-center justify-between">
      <div>
        <p class="text-xs font-bold text-primary uppercase tracking-wider">Laporan Masuk</p>
        <p class="text-3xl font-black text-primary mt-1">{{ $totalLaporan }}</p>
        <p class="text-[11px] text-text-secondary mt-1">Rencana tugas harian</p>
      </div>
      <div class="w-12 h-12 rounded-xl bg-[#E0F7FA] text-[#00838F] flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[26px]">task</span>
      </div>
    </div>
  </div>

  <!-- Filter & Search Section -->
  <div class="p-4 rounded-2xl bg-white border border-border-hairline shadow-xs">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col md:flex-row items-center gap-3">
      <!-- Search -->
      <div class="relative flex-1 w-full">
        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[20px] text-text-secondary">
          search
        </span>
        <input
          type="text"
          name="search"
          value="{{ request('search') }}"
          placeholder="Cari berdasarkan nama, NISN, atau kelas..."
          class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
      </div>

      <!-- Filter Kelas -->
      <div class="w-full md:w-48">
        <select
          name="kelas"
          class="w-full py-2.5 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Semua Kelas</option>
          @foreach($daftarKelas as $kls)
            <option value="{{ $kls }}" {{ request('kelas') === $kls ? 'selected' : '' }}>
              {{ $kls }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Scope Filter (for Teacher) -->
      @if($currentUser->isGuru())
        <div class="w-full md:w-56">
          <select
            name="filter_scope"
            class="w-full py-2.5 px-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-xs font-semibold text-on-surface focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary"
          >
            <option value="">Seluruh Siswa PKL</option>
            <option value="bimbingan_saya" {{ request('filter_scope') === 'bimbingan_saya' ? 'selected' : '' }}>
              Hanya Bimbingan Saya
            </option>
          </select>
        </div>
      @endif

      <!-- Actions -->
      <div class="flex items-center gap-2 w-full md:w-auto">
        <button
          type="submit"
          class="px-5 py-2.5 rounded-xl bg-primary hover:bg-[#006e7e] text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center gap-1.5"
        >
          <span class="material-symbols-outlined text-[16px]">filter_list</span>
          <span>Filter</span>
        </button>
        <a
          href="{{ route('admin.dashboard') }}"
          class="px-4 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-gray-50 text-text-secondary font-bold text-xs transition-colors"
        >
          Reset
        </a>
      </div>
    </form>
  </div>

  <!-- Monitoring Students Table -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs overflow-hidden">
    <div class="p-5 border-b border-border-hairline flex items-center justify-between">
      <div>
        <h2 class="text-base font-black text-on-surface">Daftar Pemantauan Siswa Hari Ini</h2>
        <p class="text-xs text-text-secondary mt-0.5">
          Menampilkan {{ $siswaList->count() }} siswa
        </p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-[#FAFDFE] border-b border-border-hairline text-[11px] font-bold text-text-secondary uppercase tracking-wider">
            <th class="py-3 px-4">Siswa</th>
            <th class="py-3 px-4">Kelas & Jurusan</th>
            <th class="py-3 px-4">Tempat PKL</th>
            <th class="py-3 px-4">Status Absensi</th>
            <th class="py-3 px-4">Bukti Foto Presensi</th>
            <th class="py-3 px-4">Laporan Harian</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border-hairline text-xs">
          @forelse($siswaList as $siswa)
            @php
              $absen = $siswa->absensi->first();
              $laporan = $siswa->laporanHarian->first();
            @endphp
            <tr class="hover:bg-primary-soft/10 transition-colors">
              <!-- Siswa -->
              <td class="py-3.5 px-4">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr($siswa->name, 0, 1)) }}
                  </div>
                  <div>
                    <p class="font-extrabold text-on-surface">{{ $siswa->name }}</p>
                    <p class="text-[11px] text-text-secondary">NISN: {{ $siswa->nisn ?? '-' }}</p>
                  </div>
                </div>
              </td>

              <!-- Kelas & Jurusan -->
              <td class="py-3.5 px-4 font-semibold text-on-surface">
                <p>{{ $siswa->kelas ?? '-' }}</p>
                <p class="text-[11px] text-text-secondary truncate max-w-[150px]">{{ $siswa->jurusan ?? '-' }}</p>
              </td>

              <!-- Tempat PKL -->
              <td class="py-3.5 px-4">
                <p class="font-bold text-on-surface truncate max-w-[160px]">{{ $siswa->tempatPkl?->nama_perusahaan ?? '-' }}</p>
                <p class="text-[11px] text-text-secondary">Pembimbing: {{ $siswa->guruPembimbing?->name ?? '-' }}</p>
              </td>

              <!-- Status Absensi -->
              <td class="py-3.5 px-4">
                @if($absen)
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-mint-soft text-status-success">
                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                    <span>Hadir ({{ substr($absen->jam, 0, 5) }} WIB)</span>
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-soft text-status-warning">
                    <span class="material-symbols-outlined text-[13px]">schedule</span>
                    <span>Belum Absen</span>
                  </span>
                @endif
              </td>

              <!-- Bukti Foto Presensi -->
              <td class="py-3.5 px-4">
                @if($absen)
                  <div class="flex items-center gap-2">
                    <!-- Foto Wajah Thumb -->
                    <a
                      href="{{ $absen->foto_url }}"
                      target="_blank"
                      class="w-8 h-8 rounded-lg bg-gray-100 border border-border-hairline overflow-hidden flex items-center justify-center hover:opacity-80 transition-opacity"
                      title="Lihat Foto Selfie"
                    >
                      @if($absen->foto_url)
                        <img src="{{ $absen->foto_url }}" alt="Foto Selfie" class="w-full h-full object-cover">
                      @else
                        <span class="material-symbols-outlined text-[16px] text-primary">face</span>
                      @endif
                    </a>

                    @if($absen->ttd_url)
                      <!-- TTD Thumb (Jika data riwayat lama memiliki TTD) -->
                      <a
                        href="{{ $absen->ttd_url }}"
                        target="_blank"
                        class="w-8 h-8 rounded-lg bg-white border border-border-hairline p-0.5 overflow-hidden flex items-center justify-center hover:opacity-80 transition-opacity"
                        title="Lihat Tanda Tangan"
                      >
                        <img src="{{ $absen->ttd_url }}" alt="TTD" class="w-full h-full object-contain">
                      </a>
                    @endif
                  </div>
                @else
                  <span class="text-[11px] text-text-secondary/70 italic">Belum ada</span>
                @endif
              </td>

              <!-- Laporan Harian -->
              <td class="py-3.5 px-4">
                @if($laporan)
                  <div>
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-status-success mb-0.5">
                      <span class="material-symbols-outlined text-[13px]">task_alt</span>
                      <span>Terkirim</span>
                    </span>
                    <p class="text-[11px] text-text-secondary line-clamp-1 max-w-[200px]" title="{{ $laporan->rencana_tugas }}">
                      {{ $laporan->rencana_tugas }}
                    </p>
                  </div>
                @else
                  <span class="text-[11px] text-status-warning font-semibold">Belum kirim</span>
                @endif
              </td>

              <!-- Aksi -->
              <td class="py-3.5 px-4 text-center">
                <a
                  href="{{ route('admin.siswa.detail', $siswa->id) }}"
                  class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-border-hairline bg-white hover:bg-primary-soft hover:text-primary hover:border-primary/30 text-on-surface font-bold text-xs transition-colors shadow-xs"
                >
                  <span class="material-symbols-outlined text-[15px]">visibility</span>
                  <span>Detail</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-text-secondary">
                Tidak ada data siswa yang cocok dengan filter yang dipilih.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
