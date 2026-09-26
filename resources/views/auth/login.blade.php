<!-- File: resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport">
  <title>Masuk — Sistem Monitoring Siswa PKL</title>
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
      min-height: max(884px, 100dvh);
      font-family: 'Nunito Sans', sans-serif;
    }
  </style>
</head>
<body class="bg-[#F0F7FB] min-h-screen text-on-surface flex justify-center selection:bg-primary-soft selection:text-primary">
  <div class="w-full max-w-[480px] min-h-screen flex flex-col justify-between relative bg-background shadow-2xl overflow-x-hidden">
    <!-- Top Atmosphere Gradient -->
    <div class="absolute top-0 left-0 right-0 h-80 bg-gradient-to-b from-[#BAE8F8] via-[#DCF3FB] to-transparent pointer-events-none z-0"></div>

    <!-- Main Container -->
    <div class="relative z-10 flex-1 flex flex-col justify-between px-6 pt-12 pb-8">
      <div>
        <!-- Brand & School Logo Section -->
        <div class="flex flex-col items-center text-center mt-4 mb-8">
          <div class="w-20 h-20 rounded-2xl bg-white border border-border-hairline shadow-sm flex items-center justify-center mb-4 transition-transform active:scale-95 p-2 overflow-hidden">
            <img src="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}" alt="Logo SMK Plus Pelita Nusantara" class="w-full h-full object-contain">
          </div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-primary-soft text-primary tracking-wide uppercase mb-2">
            <span class="material-symbols-outlined text-[14px]">verified</span>
            Sistem Monitoring PKL
          </span>
          <h1 class="text-2xl font-black text-on-surface tracking-tight">Selamat Datang</h1>
          <p class="text-sm text-text-secondary mt-1 max-w-[280px]">
            Masuk ke akun Anda untuk presensi harian, laporan tugas, atau monitoring.
          </p>
        </div>

        <!-- Alert Error Message -->
        @if($errors->any() || session('error'))
          <div class="mb-5 p-3.5 rounded-xl bg-[#FDE8E8] border border-[#F8B4B4] text-status-error flex items-start gap-2.5 text-xs font-semibold leading-relaxed shadow-xs">
            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">error</span>
            <div>
              @if(session('error'))
                <p>{{ session('error') }}</p>
              @endif
              @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          </div>
        @endif

        @if(session('success'))
          <div class="mb-5 p-3.5 rounded-xl bg-mint-soft border border-[#A4E3BE] text-status-success flex items-start gap-2.5 text-xs font-semibold leading-relaxed shadow-xs">
            <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">check_circle</span>
            <p>{{ session('success') }}</p>
          </div>
        @endif

        <!-- Login Card Form -->
        <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-6 mb-6">
          <form action="{{ route('login.post') }}" method="POST" id="loginForm" class="space-y-4">
            @csrf
            <!-- Input: Username / Email -->
            <div>
              <label for="login" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                Username / Email
              </label>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-[20px] text-text-secondary pointer-events-none">
                  person
                </span>
                <input
                  type="text"
                  id="login"
                  name="login"
                  value="{{ old('login') }}"
                  placeholder="Masukkan username atau email"
                  required
                  autocomplete="username"
                  class="w-full pl-11 pr-4 py-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-sm text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                >
              </div>
            </div>

            <!-- Input: Password -->
            <div>
              <label for="password" class="block text-xs font-bold text-on-surface uppercase tracking-wider mb-2">
                Kata Sandi
              </label>
              <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-3.5 text-[20px] text-text-secondary pointer-events-none">
                  lock
                </span>
                <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="Masukkan kata sandi"
                  required
                  autocomplete="current-password"
                  class="w-full pl-11 pr-11 py-3 rounded-xl border border-border-hairline bg-[#FAFDFE] text-sm text-on-surface placeholder:text-text-secondary/60 focus:bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                >
                <button
                  type="button"
                  id="togglePassword"
                  class="absolute right-3 text-text-secondary hover:text-on-surface p-1 transition-colors"
                  aria-label="Tampilkan kata sandi"
                >
                  <span class="material-symbols-outlined text-[20px]" id="toggleIcon">visibility</span>
                </button>
              </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-border-hairline text-primary focus:ring-primary">
                <span class="text-xs text-text-secondary font-medium">Ingat saya</span>
              </label>
            </div>

            <!-- CTA Submit Button -->
            <button
              type="submit"
              class="w-full py-3.5 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-md transition-all flex items-center justify-center gap-2"
            >
              <span>Masuk ke Akun</span>
              <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
          </form>
        </div>
      </div>

      <!-- Footer Info -->
      <footer class="mt-8 text-center text-xs text-text-secondary">
        <p class="font-semibold text-on-surface">SMK Plus Pelita Nusantara &mdash; Sistem Monitoring PKL</p>
        <p class="text-[11px] text-text-secondary/80 mt-0.5">Versi WEx 1.0 &bull; The Beyonders Development</p>
      </footer>
    </div>
  </div>

  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', () => {
      const isPassword = passwordInput.getAttribute('type') === 'password';
      passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
      toggleIcon.textContent = isPassword ? 'visibility_off' : 'visibility';
    });
  </script>
</body>
</html>
