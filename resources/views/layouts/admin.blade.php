<!-- File: resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'Panel Monitoring PKL - Sekolah')</title>
  <link rel="icon" type="image/png" href="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,300..900;1,6..12,300..900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-primary": "#ffffff",
            "background": "#F7FBFC",
            "surface-white": "#FFFFFF",
            "status-success": "#18794E",
            "status-error": "#B42318",
            "status-warning": "#A15C00",
            "on-surface": "#0E2933",
            "on-surface-variant": "#4E6169",
            "primary": "#008294",
            "primary-soft": "#DDF4F8",
            "mint-soft": "#E4F7EB",
            "yellow-soft": "#FFF5D6",
            "border-hairline": "#E7EFF3",
            "text-secondary": "#637882"
          },
          fontFamily: {
            sans: ["Nunito Sans", "sans-serif"]
          }
        }
      }
    };
  </script>
  <style>
    body {
      font-family: 'Nunito Sans', sans-serif;
    }
  </style>
</head>
<body class="bg-[#F0F7FB] min-h-screen text-on-surface antialiased flex flex-col selection:bg-primary-soft selection:text-primary">
  <div class="flex min-h-screen">
    <!-- Desktop Sidebar -->
    <aside class="w-64 bg-white border-r border-border-hairline flex flex-col shrink-0">
      <!-- Brand Header -->
      <div class="p-6 border-b border-border-hairline flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white border border-border-hairline p-1 flex items-center justify-center shadow-xs overflow-hidden">
          <img src="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}" alt="Logo SMK Plus Pelita Nusantara" class="w-full h-full object-contain">
        </div>
        <div>
          <h1 class="text-base font-extrabold text-on-surface leading-tight">PKL Monitoring</h1>
          <p class="text-xs text-text-secondary font-medium">SMK Plus Pelita Nusantara</p>
        </div>
      </div>

      <!-- Navigation Links -->
      <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
        <!-- Section: Monitoring & Laporan -->
        <div>
          <p class="px-3.5 mb-2 text-[11px] font-bold text-text-secondary uppercase tracking-wider">Monitoring &amp; Rekap</p>
          <div class="space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.siswa.detail') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">dashboard</span>
              <span>Dashboard Monitoring</span>
            </a>
            <a href="{{ route('admin.rekap.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.rekap.*') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">assessment</span>
              <span>Rekapitulasi &amp; Ekspor</span>
            </a>
          </div>
        </div>

        <!-- Section: Master Data PKL (Khusus Admin) -->
        @if(Auth::user()->isAdmin())
        <div>
          <p class="px-3.5 mb-2 text-[11px] font-bold text-text-secondary uppercase tracking-wider">Master Data PKL</p>
          <div class="space-y-1">
            <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.siswa.index') || request()->routeIs('admin.siswa.create') || request()->routeIs('admin.siswa.edit') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">groups</span>
              <span>Siswa &amp; Penempatan</span>
            </a>
            <a href="{{ route('admin.guru.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.guru.*') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">person_outline</span>
              <span>Guru Pembimbing</span>
            </a>
            <a href="{{ route('admin.kelola-admin.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.kelola-admin.*') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
              <span>Administrator Sistem</span>
            </a>
            <a href="{{ route('admin.tempat-pkl.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.tempat-pkl.*') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">apartment</span>
              <span>Tempat PKL Industri</span>
            </a>
            <a href="{{ route('admin.periode.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold text-sm transition-colors {{ request()->routeIs('admin.periode.*') ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-background hover:text-on-surface' }}">
              <span class="material-symbols-outlined text-[20px]">date_range</span>
              <span>Periode PKL</span>
            </a>
          </div>
        </div>
        @endif
      </nav>

      <!-- User Info & Logout -->
      <div class="p-4 border-t border-border-hairline bg-[#FAFDFE]">
        <div class="flex items-center gap-3 mb-3">
          <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-xs font-bold text-on-surface truncate">{{ Auth::user()->name }}</p>
            <p class="text-[11px] text-text-secondary uppercase tracking-wider font-semibold">{{ Auth::user()->role }}</p>
          </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg border border-border-hairline bg-white hover:bg-red-50 text-status-error font-bold text-xs transition-colors shadow-xs">
            <span class="material-symbols-outlined text-[16px]">logout</span>
            <span>Keluar Sistem</span>
          </button>
        </form>
      </div>
    </aside>

    <!-- Main Content Layout -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar Header -->
      <header class="h-16 bg-white border-b border-border-hairline px-8 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2 text-sm text-text-secondary">
          <span class="material-symbols-outlined text-[18px]">calendar_today</span>
          <span>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
        <div class="flex items-center gap-4">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ Auth::user()->role === 'admin' ? 'bg-[#EDE7F6] text-[#5E35B1]' : 'bg-primary-soft text-primary' }}">
            <span class="material-symbols-outlined text-[14px]">shield_person</span>
            <span>Role: {{ ucfirst(Auth::user()->role) }}</span>
          </span>
          <a href="{{ route('siswa.dashboard') }}" target="_blank" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">visibility</span>
            <span>Lihat Tampilan Siswa</span>
          </a>
        </div>
      </header>

      <!-- Main Body -->
      <main class="flex-1 p-8 overflow-y-auto">
        <!-- Flash Alert Notification -->
        @if(session('success'))
          <div class="mb-6 p-4 rounded-xl bg-mint-soft border border-[#A4E3BE] text-status-success flex items-start gap-3 shadow-xs">
            <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">check_circle</span>
            <p class="text-sm font-semibold leading-snug">{{ session('success') }}</p>
          </div>
        @endif

        @if(session('error') || $errors->any())
          <div class="mb-6 p-4 rounded-xl bg-[#FDE8E8] border border-[#F8B4B4] text-status-error flex items-start gap-3 shadow-xs">
            <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">error</span>
            <div class="text-sm font-semibold leading-snug">
              @if(session('error'))
                <p>{{ session('error') }}</p>
              @endif
              @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
              @endforeach
            </div>
          </div>
        @endif

        @yield('content')
      </main>
    </div>
  </div>
  @stack('scripts')
</body>
</html>
