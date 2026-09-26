<!-- File: resources/views/layouts/siswa.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport">
  <title>@yield('title', 'Sistem Monitoring Siswa PKL')</title>
  <link rel="icon" type="image/png" href="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,300..900;1,6..12,300..900&amp;display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
  <style>
    @layer base {
      html, body {
        width: 100%;
        margin: 0;
        padding: 0;
        min-height: 100vh;
      }
      body {
        overscroll-behavior-y: none;
      }
      .pb-safe {
        padding-bottom: env(safe-area-inset-bottom, 0px);
      }
      .pt-safe {
        padding-top: env(safe-area-inset-top, 0px);
      }
    }
    ::-webkit-scrollbar {
      display: none;
    }
  </style>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-primary": "#ffffff",
            "tertiary-fixed": "#98f0ff",
            "surface-bright": "#f3faff",
            "on-secondary-container": "#673800",
            "background": "#F7FBFC",
            "surface-container-low": "#e6f6ff",
            "on-tertiary": "#ffffff",
            "surface-white": "#FFFFFF",
            "status-success": "#18794E",
            "on-error": "#ffffff",
            "outline": "#6e797b",
            "on-surface": "#0E2933",
            "status-error": "#B42318",
            "error": "#ba1a1a",
            "primary-container": "#0b7c8a",
            "primary-fixed": "#9af0ff",
            "accent-deep": "#B85C00",
            "surface-container": "#daf2fe",
            "secondary": "#E67E22",
            "tertiary": "#00616c",
            "status-warning": "#A15C00",
            "on-surface-variant": "#4E6169",
            "primary": "#008294",
            "surface-container-lowest": "#ffffff",
            "background-canvas": "#F7FBFC",
            "outline-variant": "#e2eaf0",
            "text-secondary": "#637882",
            "primary-soft": "#DDF4F8",
            "mint-soft": "#E4F7EB",
            "yellow-soft": "#FFF5D6",
            "border-hairline": "#E7EFF3"
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.75rem",
            "xl": "1rem",
            "2xl": "1.25rem",
            "3xl": "1.75rem",
            "full": "9999px"
          },
          fontFamily: {
            sans: ["Nunito Sans", "sans-serif"],
            "body-md": ["Nunito Sans", "sans-serif"],
            "headline-md": ["Nunito Sans", "sans-serif"],
            "headline-sm": ["Nunito Sans", "sans-serif"]
          }
        }
      }
    };
  </script>
  <style>
    body {
      min-height: max(884px, 100dvh);
      font-family: 'Nunito Sans', sans-serif;
    }

    /* Floating Curved Bottom Navigation Bar */
    .wex-bottom-nav {
      position: fixed;
      bottom: 12px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 1.5rem);
      max-width: 440px;
      height: 74px;
      z-index: 50;
      filter: drop-shadow(0 14px 28px rgba(14, 41, 51, 0.12)) drop-shadow(0 4px 10px rgba(14, 41, 51, 0.06));
    }

    .wex-nav-svg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
    }

    .wex-nav-items {
      position: relative;
      z-index: 10;
      display: flex;
      width: 100%;
      height: 100%;
    }

    .wex-nav-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      color: #637882;
      position: relative;
      height: 100%;
      padding-top: 6px;
      padding-bottom: 10px;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .wex-nav-item .icon {
      font-size: 24px;
      color: #637882;
      transition: all 0.25s ease;
    }

    .wex-nav-item .label {
      font-size: 10.5px;
      font-weight: 600;
      color: #637882;
      margin-top: 2px;
      transition: all 0.25s ease;
      white-space: nowrap;
    }

    /* Active State */
    .wex-nav-item.active .label {
      font-weight: 800;
      color: #0E2933;
      margin-top: 24px;
    }

    .wex-nav-item.active .active-bubble {
      position: absolute;
      top: -24px;
      width: 54px;
      height: 54px;
      background: #FFFFFF;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 3.5px solid #008294;
      box-shadow: 0 8px 20px rgba(0, 130, 148, 0.25);
      transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .wex-nav-item.active .active-bubble .icon {
      color: #008294;
      font-size: 26px;
    }

    .wex-home-indicator {
      position: absolute;
      bottom: 5px;
      left: 50%;
      transform: translateX(-50%);
      width: 100px;
      height: 3.5px;
      background: #CBD5E1;
      border-radius: 9999px;
      z-index: 15;
      pointer-events: none;
    }
  </style>
</head>
<body class="bg-[#F0F7FB] min-h-screen font-body-md text-on-surface flex justify-center selection:bg-primary-soft selection:text-primary">
  <div class="w-full max-w-[480px] min-h-screen flex flex-col justify-between relative bg-background shadow-2xl overflow-x-hidden">
    <!-- Top Atmosphere Gradient -->
    <div class="absolute top-0 left-0 right-0 h-80 bg-gradient-to-b from-[#BAE8F8] via-[#DCF3FB] to-transparent pointer-events-none z-0"></div>

    <!-- Main Content Area -->
    <main class="relative z-10 flex-1 flex flex-col px-5 pt-8 pb-32">
      <!-- Flash Alert Notification -->
      @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-mint-soft border border-[#A4E3BE] text-status-success flex items-start gap-3 shadow-xs">
          <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">check_circle</span>
          <p class="text-sm font-semibold leading-snug">{{ session('success') }}</p>
        </div>
      @endif

      @if(session('warning'))
        <div class="mb-4 p-4 rounded-xl bg-yellow-soft border border-[#F3D58C] text-status-warning flex items-start gap-3 shadow-xs">
          <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">warning</span>
          <p class="text-sm font-semibold leading-snug">{{ session('warning') }}</p>
        </div>
      @endif

      @if(session('error') || $errors->any())
        <div class="mb-4 p-4 rounded-xl bg-[#FDE8E8] border border-[#F8B4B4] text-status-error flex items-start gap-3 shadow-xs">
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

    <!-- Curved Bottom Navigation Bar (Sesuai Referensi Mobile Navigation) -->
    @php
      $activeNavIdx = 0;
      if (request()->routeIs('siswa.absensi*')) $activeNavIdx = 1;
      elseif (request()->routeIs('siswa.laporan*')) $activeNavIdx = 2;
      elseif (request()->routeIs('siswa.riwayat*')) $activeNavIdx = 3;
      elseif (request()->routeIs('siswa.profil*')) $activeNavIdx = 4;

      // Inisialisasi kurva SVG statis awal di server untuk SSR tanpa layout shift
      $initW = 440;
      $initH = 74;
      $initR = 26;
      $initTabW = $initW / 5;
      $initCx = ($activeNavIdx + 0.5) * $initTabW;
      $initNotchR = 38;
      $initDepth = 22;

      if ($activeNavIdx === 0) {
        $initScoopEnd = $initCx + $initNotchR;
        $initCp3x = $initCx + $initNotchR * 0.35;
        $initCp4x = $initCx + $initNotchR * 0.55;
        $initPath = "M 0 {$initR} Q 0 0 {$initR} 0 C " . ($initCx * 0.4) . " 0, " . ($initCx * 0.7) . " {$initDepth}, {$initCx} {$initDepth} C {$initCp3x} {$initDepth}, {$initCp4x} 0, {$initScoopEnd} 0";
      } elseif ($activeNavIdx === 4) {
        $initScoopStart = $initCx - $initNotchR;
        $initCp1x = $initCx - $initNotchR * 0.55;
        $initCp2x = $initCx - $initNotchR * 0.35;
        $initPath = "M 0 {$initR} Q 0 0 {$initR} 0 L {$initScoopStart} 0 C {$initCp1x} 0, {$initCp2x} {$initDepth}, {$initCx} {$initDepth} C " . ($initCx + ($initW - $initCx) * 0.3) . " {$initDepth}, " . ($initCx + ($initW - $initCx) * 0.6) . " 0, " . ($initW - $initR) . " 0";
      } else {
        $initScoopStart = $initCx - $initNotchR;
        $initScoopEnd = $initCx + $initNotchR;
        $initCp1x = $initCx - $initNotchR * 0.55;
        $initCp2x = $initCx - $initNotchR * 0.35;
        $initCp3x = $initCx + $initNotchR * 0.35;
        $initCp4x = $initCx + $initNotchR * 0.55;
        $initPath = "M 0 {$initR} Q 0 0 {$initR} 0 L {$initScoopStart} 0 C {$initCp1x} 0, {$initCp2x} {$initDepth}, {$initCx} {$initDepth} C {$initCp3x} {$initDepth}, {$initCp4x} 0, {$initScoopEnd} 0";
      }

      $initPath .= " L " . ($initW - $initR) . " 0 Q {$initW} 0 {$initW} {$initR} L {$initW} " . ($initH - $initR) . " Q {$initW} {$initH} " . ($initW - $initR) . " {$initH} L {$initR} {$initH} Q 0 {$initH} 0 " . ($initH - $initR) . " L 0 {$initR} Z";
    @endphp

    <nav class="wex-bottom-nav" id="wexNav" data-active-index="{{ $activeNavIdx }}">
      <svg class="wex-nav-svg" id="wexNavSvg" viewBox="0 0 {{ $initW }} {{ $initH }}" fill="none">
        <path id="wexNavPath" d="{{ $initPath }}" fill="#FFFFFF" />
      </svg>
      <div class="wex-nav-items">
        <!-- Tab 0: Beranda -->
        <a href="{{ route('siswa.dashboard') }}" class="wex-nav-item {{ $activeNavIdx === 0 ? 'active' : '' }}">
          @if($activeNavIdx === 0)
            <div class="active-bubble">
              <span class="material-symbols-outlined icon">home</span>
            </div>
          @else
            <span class="material-symbols-outlined icon">home</span>
          @endif
          <span class="label">Beranda</span>
        </a>

        <!-- Tab 1: Absensi -->
        <a href="{{ route('siswa.absensi') }}" class="wex-nav-item {{ $activeNavIdx === 1 ? 'active' : '' }}">
          @if($activeNavIdx === 1)
            <div class="active-bubble">
              <span class="material-symbols-outlined icon">photo_camera</span>
            </div>
          @else
            <span class="material-symbols-outlined icon">photo_camera</span>
          @endif
          <span class="label">Absensi</span>
        </a>

        <!-- Tab 2: Laporan -->
        <a href="{{ route('siswa.laporan') }}" class="wex-nav-item {{ $activeNavIdx === 2 ? 'active' : '' }}">
          @if($activeNavIdx === 2)
            <div class="active-bubble">
              <span class="material-symbols-outlined icon">assignment</span>
            </div>
          @else
            <span class="material-symbols-outlined icon">assignment</span>
          @endif
          <span class="label">Laporan</span>
        </a>

        <!-- Tab 3: Riwayat -->
        <a href="{{ route('siswa.riwayat') }}" class="wex-nav-item {{ $activeNavIdx === 3 ? 'active' : '' }}">
          @if($activeNavIdx === 3)
            <div class="active-bubble">
              <span class="material-symbols-outlined icon">history</span>
            </div>
          @else
            <span class="material-symbols-outlined icon">history</span>
          @endif
          <span class="label">Riwayat</span>
        </a>

        <!-- Tab 4: Profil -->
        <a href="{{ route('siswa.profil') }}" class="wex-nav-item {{ $activeNavIdx === 4 ? 'active' : '' }}">
          @if($activeNavIdx === 4)
            <div class="active-bubble">
              <span class="material-symbols-outlined icon">person</span>
            </div>
          @else
            <span class="material-symbols-outlined icon">person</span>
          @endif
          <span class="label">Profil</span>
        </a>
      </div>
      <div class="wex-home-indicator"></div>
    </nav>
  </div>

  <script>
    (function() {
      function createNavPath(w, h, activeIndex, totalTabs = 5, r = 26) {
        const tabWidth = w / totalTabs;
        const cx = (activeIndex + 0.5) * tabWidth;
        const notchRadius = 38;
        const depth = 22;

        let d = '';

        if (activeIndex === 0) {
          const scoopEnd = cx + notchRadius;
          const cp3x = cx + notchRadius * 0.35;
          const cp4x = cx + notchRadius * 0.55;

          d = `M 0 ${r} Q 0 0 ${r} 0`;
          d += ` C ${cx * 0.4} 0, ${cx * 0.7} ${depth}, ${cx} ${depth}`;
          d += ` C ${cp3x} ${depth}, ${cp4x} 0, ${scoopEnd} 0`;
        } else if (activeIndex === totalTabs - 1) {
          const scoopStart = cx - notchRadius;
          const cp1x = cx - notchRadius * 0.55;
          const cp2x = cx - notchRadius * 0.35;

          d = `M 0 ${r} Q 0 0 ${r} 0 L ${scoopStart} 0`;
          d += ` C ${cp1x} 0, ${cp2x} ${depth}, ${cx} ${depth}`;
          d += ` C ${cx + (w - cx) * 0.3} ${depth}, ${cx + (w - cx) * 0.6} 0, ${w - r} 0`;
        } else {
          const scoopStart = cx - notchRadius;
          const scoopEnd = cx + notchRadius;
          const cp1x = cx - notchRadius * 0.55;
          const cp2x = cx - notchRadius * 0.35;
          const cp3x = cx + notchRadius * 0.35;
          const cp4x = cx + notchRadius * 0.55;

          d = `M 0 ${r} Q 0 0 ${r} 0 L ${scoopStart} 0`;
          d += ` C ${cp1x} 0, ${cp2x} ${depth}, ${cx} ${depth}`;
          d += ` C ${cp3x} ${depth}, ${cp4x} 0, ${scoopEnd} 0`;
        }

        d += ` L ${w - r} 0 Q ${w} 0 ${w} ${r}`;
        d += ` L ${w} ${h - r} Q ${w} ${h} ${w - r} ${h}`;
        d += ` L ${r} ${h} Q 0 ${h} 0 ${h - r}`;
        d += ` L 0 ${r} Z`;

        return d;
      }

      function updateNavPath() {
        const navEl = document.getElementById('wexNav');
        const svgEl = document.getElementById('wexNavSvg');
        const pathEl = document.getElementById('wexNavPath');
        if (!navEl || !svgEl || !pathEl) return;

        const w = navEl.offsetWidth;
        const h = navEl.offsetHeight;
        const activeIdx = parseInt(navEl.getAttribute('data-active-index') || '0', 10);

        svgEl.setAttribute('viewBox', `0 0 ${w} ${h}`);
        pathEl.setAttribute('d', createNavPath(w, h, activeIdx, 5, 26));
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateNavPath);
      } else {
        updateNavPath();
      }

      window.addEventListener('resize', updateNavPath);
    })();
  </script>

  @stack('scripts')
</body>
</html>
