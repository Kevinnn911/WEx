<!-- File: resources/views/siswa/profil.blade.php -->
@extends('layouts.siswa')

@section('title', 'Profil Siswa - Sistem Monitoring PKL')

@section('content')
<div class="space-y-5">
  <!-- Top App Bar Navigation -->
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <a href="{{ route('siswa.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
      </a>
      <h1 class="text-lg font-black text-on-surface">Profil Siswa</h1>
    </div>
    <span class="text-xs font-bold text-primary bg-primary-soft px-3 py-1 rounded-full uppercase tracking-wider">
      {{ $user->role }}
    </span>
  </div>

  <!-- Header Card: Avatar & Student Identity -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 text-center">
    <div class="w-20 h-20 rounded-full bg-primary-soft text-primary border-4 border-white shadow-md mx-auto flex items-center justify-center font-black text-2xl mb-3">
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <h2 class="text-base font-black text-on-surface">{{ $user->name }}</h2>
    <p class="text-xs font-semibold text-text-secondary mt-0.5">NISN: {{ $user->nisn ?? '-' }}</p>
    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-background border border-border-hairline text-xs font-semibold text-text-secondary mt-2">
      <span class="material-symbols-outlined text-[14px] text-primary">school</span>
      <span>{{ $user->kelas ?? 'XI TKJ 1' }} &bull; {{ $user->jurusan ?? 'Teknik Komputer dan Jaringan' }}</span>
    </div>
  </div>

  <!-- SECTION 1: Informasi PKL -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-5 space-y-3.5">
    <div class="flex items-center gap-2 pb-2 border-b border-border-hairline">
      <span class="material-symbols-outlined text-[20px] text-primary">corporate_fare</span>
      <h3 class="text-xs font-extrabold text-on-surface uppercase tracking-wider">Informasi Penempatan PKL</h3>
    </div>

    <div class="space-y-3 text-xs">
      <div class="flex items-start justify-between">
        <span class="text-text-secondary font-medium">Tempat PKL / Industri:</span>
        <span class="font-bold text-on-surface text-right max-w-[200px]">
          {{ $user->tempatPkl ? $user->tempatPkl->nama_perusahaan : 'Belum Ditempatkan' }}
        </span>
      </div>

      <div class="flex items-start justify-between">
        <span class="text-text-secondary font-medium">Bidang Usaha:</span>
        <span class="font-bold text-on-surface text-right">
          {{ $user->tempatPkl ? $user->tempatPkl->bidang : '-' }}
        </span>
      </div>

      <div class="flex items-start justify-between">
        <span class="text-text-secondary font-medium">Alamat Perusahaan:</span>
        <span class="font-semibold text-text-secondary text-right max-w-[200px]">
          {{ $user->tempatPkl ? $user->tempatPkl->alamat : '-' }}
        </span>
      </div>

      <div class="flex items-start justify-between">
        <span class="text-text-secondary font-medium">Guru Pembimbing:</span>
        <span class="font-bold text-on-surface text-right">
          {{ $user->guruPembimbing ? $user->guruPembimbing->name : 'Belum Ditugaskan' }}
        </span>
      </div>

      <div class="flex items-start justify-between">
        <span class="text-text-secondary font-medium">Periode PKL:</span>
        <span class="font-semibold text-primary text-right">
          {{ $periode ? $periode->nama_periode : 'Periode Belum Aktif' }}
        </span>
      </div>
    </div>
  </div>

  <!-- SECTION 2: Akun & Logout -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-5 space-y-3.5">
    <div class="flex items-center gap-2 pb-2 border-b border-border-hairline">
      <span class="material-symbols-outlined text-[20px] text-primary">account_circle</span>
      <h3 class="text-xs font-extrabold text-on-surface uppercase tracking-wider">Informasi Akun</h3>
    </div>

    <div class="space-y-3 text-xs">
      <div class="flex items-center justify-between">
        <span class="text-text-secondary font-medium">Username:</span>
        <span class="font-bold text-on-surface">{{ $user->username }}</span>
      </div>

      <div class="flex items-center justify-between">
        <span class="text-text-secondary font-medium">Email Terdaftar:</span>
        <span class="font-bold text-on-surface">{{ $user->email ?? '-' }}</span>
      </div>
    </div>

    <div class="pt-3 border-t border-border-hairline">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button
          type="submit"
          class="w-full py-3 px-4 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-status-error font-extrabold text-xs transition-colors flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">logout</span>
          <span>Keluar dari Akun Siswa</span>
        </button>
      </form>
    </div>
  </div>

  <!-- Developer & System Identity Card -->
  <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-4">
    <div class="flex items-center gap-3.5">
      <div class="w-11 h-11 rounded-xl bg-white border border-border-hairline p-1.5 shadow-2xs flex items-center justify-center shrink-0 overflow-hidden">
        <img src="{{ asset('logo2.png') }}?v={{ filemtime(public_path('logo2.png')) }}" alt="Logo The Beyonders" class="w-full h-full object-contain">
      </div>
      <div class="flex-1 min-w-0">
        <h4 class="text-xs font-black text-on-surface truncate">The Beyonders Development</h4>
        <p class="text-[11px] text-text-secondary leading-tight mt-0.5">WEx PKL Monitoring &bull; Versi 1.0.0 Stable</p>
      </div>
    </div>
  </div>
</div>
@endsection
