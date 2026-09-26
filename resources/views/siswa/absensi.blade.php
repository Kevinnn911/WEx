<!-- File: resources/views/siswa/absensi.blade.php -->
@extends('layouts.siswa')

@section('title', 'Absensi Hari Ini — Sistem Monitoring PKL')

@section('content')
<div class="space-y-5">
  <!-- Top App Bar Navigation -->
  <div class="flex items-center justify-between">
    <div class="flex items-center gap-2">
      <a href="{{ route('siswa.dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
      </a>
      <div>
        <h1 class="text-lg font-black text-on-surface leading-tight">Absensi Hari Ini</h1>
        <p class="text-xs text-text-secondary">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
      </div>
    </div>
    <div class="text-right">
      <span class="inline-flex items-center gap-1 text-xs font-black text-primary bg-primary-soft px-2.5 py-1 rounded-full" id="liveClock">
        <span class="material-symbols-outlined text-[14px]">schedule</span>
        <span>{{ \Carbon\Carbon::now()->format('H:i:s') }} WIB</span>
      </span>
    </div>
  </div>

  @if($absensiHariIni)
    <!-- State: Sudah Absen Hari Ini (Success Confirmation Screen) -->
    <div class="rounded-2xl bg-white border border-[#A4E3BE] shadow-sm p-6 text-center space-y-4">
      <div class="w-16 h-16 rounded-full bg-mint-soft text-status-success mx-auto flex items-center justify-center">
        <span class="material-symbols-outlined text-[36px]">check_circle</span>
      </div>

      <div>
        <h2 class="text-lg font-black text-on-surface">Absensi Hari Ini Berhasil</h2>
        <p class="text-xs text-text-secondary mt-1">
          Tercatat pada <strong class="text-on-surface">{{ substr($absensiHariIni->jam, 0, 5) }} WIB</strong> • Status: Hadir
        </p>
      </div>

      <!-- Preview Bukti Absensi -->
      @if($absensiHariIni->ttd_url)
        <div class="grid grid-cols-2 gap-3 pt-2 text-left">
          <div class="p-3 rounded-xl border border-border-hairline bg-[#FAFDFE]">
            <p class="text-[11px] font-bold text-text-secondary mb-1.5 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">face</span>
              <span>Foto Selfie Wajah</span>
            </p>
            <div class="aspect-4/3 rounded-lg overflow-hidden bg-gray-100 border border-border-hairline flex items-center justify-center">
              @if($absensiHariIni->foto_url)
                <img src="{{ $absensiHariIni->foto_url }}" alt="Foto Selfie" class="w-full h-full object-cover">
              @else
                <div class="flex flex-col items-center justify-center p-2 text-text-secondary">
                  <span class="material-symbols-outlined text-[28px] text-primary">account_circle</span>
                  <span class="text-[10px] mt-1 font-semibold">Tersimpan</span>
                </div>
              @endif
            </div>
          </div>

          <div class="p-3 rounded-xl border border-border-hairline bg-[#FAFDFE]">
            <p class="text-[11px] font-bold text-text-secondary mb-1.5 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">draw</span>
              <span>Tanda Tangan</span>
            </p>
            <div class="aspect-4/3 rounded-lg overflow-hidden bg-white border border-border-hairline flex items-center justify-center p-2">
              <img src="{{ $absensiHariIni->ttd_url }}" alt="Tanda Tangan" class="w-full h-full object-contain">
            </div>
          </div>
        </div>
      @else
        <div class="pt-2 text-left max-w-xs mx-auto">
          <div class="p-3 rounded-xl border border-border-hairline bg-[#FAFDFE]">
            <p class="text-[11px] font-bold text-text-secondary mb-1.5 flex items-center gap-1">
              <span class="material-symbols-outlined text-[14px]">face</span>
              <span>Foto Selfie Wajah</span>
            </p>
            <div class="aspect-4/3 rounded-lg overflow-hidden bg-gray-100 border border-border-hairline flex items-center justify-center">
              @if($absensiHariIni->foto_url)
                <img src="{{ $absensiHariIni->foto_url }}" alt="Foto Selfie" class="w-full h-full object-cover">
              @else
                <div class="flex flex-col items-center justify-center p-2 text-text-secondary">
                  <span class="material-symbols-outlined text-[28px] text-primary">account_circle</span>
                  <span class="text-[10px] mt-1 font-semibold">Tersimpan</span>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endif

      <div class="pt-3 space-y-2">
        <a
          href="{{ route('siswa.laporan') }}"
          class="w-full py-3.5 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] shadow-sm transition-all flex items-center justify-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">assignment</span>
          <span>Lanjut Buat Laporan Harian</span>
        </a>
        <a
          href="{{ route('siswa.dashboard') }}"
          class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-text-secondary border border-border-hairline hover:bg-gray-50 flex items-center justify-center transition-colors"
        >
          Kembali ke Beranda
        </a>
      </div>
    </div>
  @else
    <!-- State: Form Absensi (Foto Selfie) -->
    <form action="{{ route('siswa.absensi.submit') }}" method="POST" id="formAbsensi" class="space-y-5">
      @csrf
      <!-- Hidden fields to hold Base64 data -->
      <input type="hidden" name="foto_wajah" id="inputFotoWajah" required>
      <input type="hidden" name="status" value="hadir">

      <!-- CARD: Kamera Selfie -->
      <div class="rounded-2xl bg-white border border-border-hairline shadow-xs p-5">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-primary">face</span>
            <h2 class="text-sm font-extrabold text-on-surface">Foto Selfie Wajah</h2>
          </div>
          <span class="text-[11px] font-bold text-status-warning bg-yellow-soft px-2 py-0.5 rounded-full" id="statusBadgeFoto">
            Wajib
          </span>
        </div>

        <!-- Camera Box Container (Aspect 4:3) -->
        <div class="relative w-full aspect-4/3 rounded-xl overflow-hidden bg-gray-900 border border-border-hairline flex items-center justify-center mb-3">
          <!-- Placeholder State -->
          <div id="cameraPlaceholder" class="flex flex-col items-center justify-center text-center p-4 text-gray-400">
            <span class="material-symbols-outlined text-[48px] text-gray-500 mb-2">no_photography</span>
            <p class="text-xs font-semibold text-gray-300">Kamera belum diaktifkan</p>
            <p class="text-[11px] text-gray-400 mt-0.5">Buka kamera webcam browser atau ambil foto langsung</p>
          </div>

          <!-- Video Element for Live Camera -->
          <video id="videoPreview" autoplay playsinline class="hidden w-full h-full object-cover transform -scale-x-100"></video>

          <!-- Captured Photo Preview -->
          <img id="imagePreview" alt="Hasil Foto Selfie" class="hidden w-full h-full object-cover">

          <!-- Hidden Canvas for Snapshot Capture -->
          <canvas id="cameraCanvas" class="hidden"></canvas>
        </div>

        <!-- File input fallback for device camera / gallery -->
        <input type="file" id="fileSelfieInput" accept="image/*" capture="user" class="hidden">

        <!-- Camera Control Actions -->
        <div class="flex flex-col gap-2">
          <div class="grid grid-cols-2 gap-2" id="cameraControlsBefore">
            <button
              type="button"
              id="btnStartCamera"
              class="py-2.5 px-3 rounded-xl text-xs font-bold text-primary bg-primary-soft hover:bg-primary/20 flex items-center justify-center gap-1.5 transition-colors"
            >
              <span class="material-symbols-outlined text-[16px]">videocam</span>
              <span>Buka Kamera</span>
            </button>

            <button
              type="button"
              id="btnUploadFile"
              class="py-2.5 px-3 rounded-xl text-xs font-bold text-text-secondary border border-border-hairline hover:bg-gray-50 flex items-center justify-center gap-1.5 transition-colors"
            >
              <span class="material-symbols-outlined text-[16px]">upload</span>
              <span>Ambil / Pilih Foto</span>
            </button>
          </div>

          <!-- Actions during live streaming -->
          <button
            type="button"
            id="btnCapturePhoto"
            class="hidden w-full py-3 px-4 rounded-xl text-xs font-extrabold text-white bg-primary hover:bg-[#006e7e] flex items-center justify-center gap-2 transition-all shadow-xs"
          >
            <span class="material-symbols-outlined text-[18px]">radio_button_checked</span>
            <span>Ambil Foto Sekarang</span>
          </button>

          <!-- Actions after photo captured -->
          <button
            type="button"
            id="btnRetakePhoto"
            class="hidden w-full py-2.5 px-4 rounded-xl text-xs font-bold text-text-secondary border border-border-hairline hover:bg-gray-50 flex items-center justify-center gap-1.5 transition-colors"
          >
            <span class="material-symbols-outlined text-[16px]">refresh</span>
            <span>Ambil Ulang Foto</span>
          </button>
        </div>
      </div>

      <!-- Submit CTA Button -->
      <div class="pt-2 pb-6">
        <button
          type="submit"
          id="btnSubmitAbsensi"
          disabled
          class="w-full py-4 px-4 rounded-xl font-extrabold text-sm text-white bg-gradient-to-r from-[#00626D] to-[#0A8597] disabled:opacity-40 disabled:cursor-not-allowed shadow-md hover:from-[#00515A] hover:to-[#086F7E] active:scale-[0.98] transition-all flex items-center justify-center gap-2"
        >
          <span>Kirim Presensi Sekarang</span>
          <span class="material-symbols-outlined text-[18px]">send</span>
        </button>
        <p class="text-[11px] text-center text-text-secondary mt-2">
          Tombol akan aktif setelah foto selfie berhasil diambil.
        </p>
      </div>
    </form>
  @endif
</div>
@endsection

@push('scripts')
<script>
  // Live Clock Updater
  const liveClockEl = document.getElementById('liveClock');
  if (liveClockEl) {
    setInterval(() => {
      const now = new Date();
      const h = String(now.getHours()).padStart(2, '0');
      const m = String(now.getMinutes()).padStart(2, '0');
      const s = String(now.getSeconds()).padStart(2, '0');
      liveClockEl.innerHTML = `<span class="material-symbols-outlined text-[14px]">schedule</span> <span>${h}:${m}:${s} WIB</span>`;
    }, 1000);
  }

  @if(!$absensiHariIni)
  let hasPhoto = false;
  let videoStream = null;

  const video = document.getElementById('videoPreview');
  const imagePreview = document.getElementById('imagePreview');
  const cameraPlaceholder = document.getElementById('cameraPlaceholder');
  const cameraCanvas = document.getElementById('cameraCanvas');
  const btnStartCamera = document.getElementById('btnStartCamera');
  const btnUploadFile = document.getElementById('btnUploadFile');
  const fileSelfieInput = document.getElementById('fileSelfieInput');
  const btnCapturePhoto = document.getElementById('btnCapturePhoto');
  const btnRetakePhoto = document.getElementById('btnRetakePhoto');
  const cameraControlsBefore = document.getElementById('cameraControlsBefore');
  const inputFotoWajah = document.getElementById('inputFotoWajah');
  const btnSubmitAbsensi = document.getElementById('btnSubmitAbsensi');
  const statusBadgeFoto = document.getElementById('statusBadgeFoto');

  // Check validation to enable submit
  function checkValidity() {
    btnSubmitAbsensi.disabled = !hasPhoto;
  }

  // 1. Camera Logic
  btnStartCamera.addEventListener('click', async () => {
    try {
      videoStream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } },
        audio: false
      });
      video.srcObject = videoStream;
      video.classList.remove('hidden');
      cameraPlaceholder.classList.add('hidden');
      imagePreview.classList.add('hidden');
      cameraControlsBefore.classList.add('hidden');
      btnCapturePhoto.classList.remove('hidden');
    } catch (err) {
      alert('Tidak dapat mengakses kamera browser secara langsung. Silakan gunakan opsi Ambil / Pilih Foto.');
    }
  });

  // Capture photo from video
  btnCapturePhoto.addEventListener('click', () => {
    cameraCanvas.width = video.videoWidth || 640;
    cameraCanvas.height = video.videoHeight || 480;
    const ctx = cameraCanvas.getContext('2d');
    // Mirror horizontally
    ctx.translate(cameraCanvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, cameraCanvas.width, cameraCanvas.height);

    const dataUrl = cameraCanvas.toDataURL('image/jpeg', 0.85);
    applyPhoto(dataUrl);

    // Stop camera stream
    if (videoStream) {
      videoStream.getTracks().forEach(track => track.stop());
      videoStream = null;
    }
  });

  // File Upload / Direct Camera Input Trigger
  btnUploadFile.addEventListener('click', () => {
    fileSelfieInput.click();
  });

  fileSelfieInput.addEventListener('change', (e) => {
    const file = e.target.files && e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (event) => {
        applyPhoto(event.target.result);
      };
      reader.readAsDataURL(file);
    }
  });

  function applyPhoto(dataUrl) {
    inputFotoWajah.value = dataUrl;
    imagePreview.src = dataUrl;
    imagePreview.classList.remove('hidden');
    video.classList.add('hidden');
    cameraPlaceholder.classList.add('hidden');
    btnCapturePhoto.classList.add('hidden');
    cameraControlsBefore.classList.add('hidden');
    btnRetakePhoto.classList.remove('hidden');

    hasPhoto = true;
    statusBadgeFoto.textContent = 'Siap';
    statusBadgeFoto.className = 'text-[11px] font-bold text-status-success bg-mint-soft px-2 py-0.5 rounded-full';
    checkValidity();
  }

  // Retake photo
  btnRetakePhoto.addEventListener('click', () => {
    hasPhoto = false;
    inputFotoWajah.value = '';
    fileSelfieInput.value = '';
    imagePreview.classList.add('hidden');
    cameraPlaceholder.classList.remove('hidden');
    btnRetakePhoto.classList.add('hidden');
    cameraControlsBefore.classList.remove('hidden');
    statusBadgeFoto.textContent = 'Wajib';
    statusBadgeFoto.className = 'text-[11px] font-bold text-status-warning bg-yellow-soft px-2 py-0.5 rounded-full';
    checkValidity();
  });
  @endif
</script>
@endpush
