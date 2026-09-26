<!-- File: resources/views/admin/siswa/import.blade.php -->
@extends('layouts.admin')

@section('title', 'Import Akun Siswa (Bulk) — Sistem Monitoring PKL')

@section('content')
<div class="space-y-6 max-w-6xl">
  <!-- Top Bar Navigation & Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.siswa.index') }}" class="w-9 h-9 rounded-xl bg-white border border-border-hairline shadow-xs flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
      </a>
      <div>
        <h1 class="text-2xl font-black text-on-surface tracking-tight">Import Akun Siswa (Bulk)</h1>
        <p class="text-xs text-text-secondary mt-0.5">
          Unggah berkas spreadsheet/CSV untuk mendaftarkan akun siswa secara massal sekaligus.
        </p>
      </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('admin.siswa.template', ['format' => 'excel']) }}" class="px-4 py-2.5 rounded-xl border border-primary/30 bg-primary-soft hover:bg-primary/20 text-primary font-extrabold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">table_view</span>
        <span>Unduh Template Excel (.xls)</span>
      </a>
      <a href="{{ route('admin.siswa.template', ['format' => 'csv']) }}" class="px-3.5 py-2.5 rounded-xl border border-border-hairline bg-white hover:bg-gray-50 text-text-secondary font-bold text-xs shadow-xs transition-colors flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">download</span>
        <span>Template CSV</span>
      </a>
    </div>
  </div>

  <!-- Flash Notification Alert -->
  @if(session('error'))
    <div class="p-4 rounded-xl bg-[#FDE8E8] border border-[#F8B4B4] text-status-error text-xs font-semibold flex items-start gap-2.5 shadow-xs">
      <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">error</span>
      <p>{{ session('error') }}</p>
    </div>
  @endif

  @if(session('success'))
    <div class="p-4 rounded-xl bg-mint-soft border border-[#A4E3BE] text-status-success text-xs font-semibold flex items-start gap-2.5 shadow-xs">
      <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5">check_circle</span>
      <p>{{ session('success') }}</p>
    </div>
  @endif

  <!-- Step 1: Panduan Pengisian Template -->
  <div class="p-6 rounded-2xl bg-white border border-border-hairline shadow-xs space-y-4">
    <div class="flex items-center justify-between border-b border-border-hairline pb-3">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px] text-primary">description</span>
        <h2 class="text-sm font-black text-on-surface uppercase tracking-wider">
          1. Panduan Kolom Template Spreadsheet / CSV
        </h2>
      </div>
      <span class="text-xs text-text-secondary font-semibold">Format didukung: Microsoft Excel (.xls) &amp; CSV (.csv)</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>nama_lengkap</span>
          <span class="text-[10px] font-bold text-status-error uppercase">Wajib</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Nama lengkap siswa sesuai data resmi sekolah (contoh: <span class="font-semibold text-on-surface">Ahmad Fauzi</span>).
        </p>
      </div>

      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>nisn</span>
          <span class="text-[10px] font-bold text-status-error uppercase">Wajib</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Nomor Induk Siswa Nasional, harus unik dan belum terdaftar di sistem.
        </p>
      </div>

      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>kelas & jurusan</span>
          <span class="text-[10px] font-bold text-status-error uppercase">Wajib</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Kelas (contoh: <span class="font-semibold text-on-surface">XI TKJ 1</span>) dan Jurusan (contoh: <span class="font-semibold text-on-surface">Teknik Komputer dan Jaringan</span>).
        </p>
      </div>

      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>username</span>
          <span class="text-[10px] font-bold text-text-secondary uppercase">Opsional</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Jika dikosongkan, username otomatis diisi dari nomor NISN siswa.
        </p>
      </div>

      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>password</span>
          <span class="text-[10px] font-bold text-text-secondary uppercase">Opsional</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Jika dikosongkan, kata sandi default otomatis disetel ke: <span class="font-bold text-primary">password123</span>.
        </p>
      </div>

      <div class="p-3.5 rounded-xl border border-border-hairline bg-[#FAFDFE]">
        <p class="font-extrabold text-on-surface mb-1 flex items-center justify-between">
          <span>tempat_pkl & guru</span>
          <span class="text-[10px] font-bold text-text-secondary uppercase">Opsional</span>
        </p>
        <p class="text-text-secondary text-[11px] leading-relaxed">
          Nama perusahaan mitra & guru pembimbing yang telah terdaftar di sistem.
        </p>
      </div>
    </div>

    <!-- Referensi Cepat Tempat PKL & Guru -->
    <details class="group rounded-xl border border-border-hairline bg-gray-50/50 p-3 text-xs">
      <summary class="cursor-pointer font-bold text-primary flex items-center justify-between">
        <span class="flex items-center gap-1.5">
          <span class="material-symbols-outlined text-[16px]">info</span>
          <span>Lihat Referensi Nama Tempat PKL & Guru Pembimbing Terdaftar</span>
        </span>
        <span class="material-symbols-outlined text-[16px] transition-transform group-open:rotate-180">expand_more</span>
      </summary>
      <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 pt-2 border-t border-border-hairline">
        <div>
          <p class="font-bold text-on-surface mb-1.5">Tempat PKL Aktif:</p>
          <div class="flex flex-wrap gap-1.5">
            @forelse($tempatPklList as $tp)
              <span class="px-2 py-0.5 rounded-md bg-white border border-border-hairline text-[11px] font-medium text-text-secondary">
                {{ $tp->nama_perusahaan }}
              </span>
            @empty
              <span class="text-text-secondary italic text-[11px]">Belum ada tempat PKL terdaftar</span>
            @endforelse
          </div>
        </div>
        <div>
          <p class="font-bold text-on-surface mb-1.5">Guru Pembimbing Aktif:</p>
          <div class="flex flex-wrap gap-1.5">
            @forelse($guruList as $gr)
              <span class="px-2 py-0.5 rounded-md bg-white border border-border-hairline text-[11px] font-medium text-text-secondary">
                {{ $gr->name }}
              </span>
            @empty
              <span class="text-text-secondary italic text-[11px]">Belum ada guru pembimbing terdaftar</span>
            @endforelse
          </div>
        </div>
      </div>
    </details>
  </div>

  <!-- Step 2: Upload Box Formulir -->
  <div class="p-6 rounded-2xl bg-white border border-border-hairline shadow-xs space-y-4">
    <div class="flex items-center justify-between border-b border-border-hairline pb-3">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px] text-primary">upload_file</span>
        <h2 class="text-sm font-black text-on-surface uppercase tracking-wider">
          2. Unggah Berkas Spreadsheet / CSV yang Telah Diisi
        </h2>
      </div>
    </div>

    <form action="{{ route('admin.siswa.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf

      <div class="border-2 border-dashed border-border-hairline hover:border-primary/50 bg-[#FAFDFE] rounded-2xl p-8 text-center transition-all">
        <input type="file" name="file" id="fileImportInput" accept=".xls,.xlsx,.csv,.txt,.xml" required class="hidden">
        <label for="fileImportInput" class="cursor-pointer flex flex-col items-center justify-center">
          <div class="w-14 h-14 rounded-2xl bg-primary-soft text-primary flex items-center justify-center mb-3 shadow-xs">
            <span class="material-symbols-outlined text-[32px]">cloud_upload</span>
          </div>
          <p class="text-sm font-extrabold text-on-surface" id="fileUploadPrompt">
            Klik untuk memilih berkas atau seret berkas Excel / CSV ke area ini
          </p>
          <p class="text-xs text-text-secondary mt-1">
            Format yang didukung: <span class="font-bold text-on-surface">.xls (Excel), .csv</span> (Maksimal 5MB)
          </p>
          <div id="fileSelectedBadge" class="hidden mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-mint-soft text-status-success font-bold text-xs">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            <span id="fileSelectedName">Nama berkas terpilih</span>
          </div>
        </label>
      </div>

      <div class="flex items-center justify-end gap-3 pt-2">
        <button
          type="submit"
          class="px-6 py-3 rounded-xl bg-primary hover:bg-[#007080] text-white font-extrabold text-xs shadow-xs transition-colors flex items-center gap-2"
        >
          <span class="material-symbols-outlined text-[18px]">rule</span>
          <span>Periksa &amp; Pratinjau Berkas</span>
        </button>
      </div>
    </form>
  </div>

  <!-- Step 3: Hasil Pratinjau & Konfirmasi Pembuatan Akun (Hanya tampil saat file telah diperiksa) -->
  @if($previewRows !== null)
    <div class="p-6 rounded-2xl bg-white border border-border-hairline shadow-sm space-y-6">
      <div class="flex items-center justify-between border-b border-border-hairline pb-4">
        <div>
          <h2 class="text-base font-black text-on-surface">
            3. Hasil Pemeriksaan Data &amp; Konfirmasi Impor
          </h2>
          <p class="text-xs text-text-secondary mt-0.5">
            Tinjau validitas setiap akun sebelum disimpan secara permanen ke dalam basis data.
          </p>
        </div>
      </div>

      <!-- KPI Summary Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl border border-border-hairline bg-[#FAFDFE]">
          <p class="text-[11px] font-bold text-text-secondary uppercase tracking-wider mb-1">Total Baris Siswa</p>
          <p class="text-2xl font-black text-on-surface">{{ $summary['total'] }}</p>
          <p class="text-[11px] text-text-secondary mt-1">Seluruh data yang terbaca dari berkas</p>
        </div>

        <div class="p-4 rounded-xl border border-[#A4E3BE] bg-mint-soft/50">
          <p class="text-[11px] font-bold text-status-success uppercase tracking-wider mb-1">Siap Diimpor (Valid)</p>
          <p class="text-2xl font-black text-status-success">{{ $summary['valid'] }}</p>
          <p class="text-[11px] text-status-success mt-1">Data lengkap dan siap dibuatkan akun</p>
        </div>

        <div class="p-4 rounded-xl border {{ $summary['invalid'] > 0 ? 'border-[#F8B4B4] bg-[#FDE8E8]/40' : 'border-border-hairline bg-[#FAFDFE]' }}">
          <p class="text-[11px] font-bold {{ $summary['invalid'] > 0 ? 'text-status-error' : 'text-text-secondary' }} uppercase tracking-wider mb-1">
            Gagal / Duplikat (Dilewati)
          </p>
          <p class="text-2xl font-black {{ $summary['invalid'] > 0 ? 'text-status-error' : 'text-text-secondary' }}">
            {{ $summary['invalid'] }}
          </p>
          <p class="text-[11px] text-text-secondary mt-1">
            {{ $summary['invalid'] > 0 ? 'Memiliki kesalahan data (tidak akan disimpan)' : 'Semua baris memenuhi syarat' }}
          </p>
        </div>
      </div>

      <!-- Tabel Pratinjau Baris Data -->
      <div class="rounded-xl border border-border-hairline overflow-hidden">
        <div class="overflow-x-auto max-h-[460px]">
          <table class="w-full text-left text-xs border-collapse">
            <thead class="sticky top-0 bg-[#FAFDFE] border-b border-border-hairline text-text-secondary uppercase tracking-wider text-[11px] font-bold z-10">
              <tr>
                <th class="py-3 px-4 text-center">No</th>
                <th class="py-3 px-4 text-center">Status</th>
                <th class="py-3 px-4">Nama Lengkap &amp; NISN</th>
                <th class="py-3 px-4">Username &amp; Password</th>
                <th class="py-3 px-4">Kelas &amp; Jurusan</th>
                <th class="py-3 px-4">Tempat PKL &amp; Pembimbing</th>
                <th class="py-3 px-4">Catatan / Validasi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-border-hairline">
              @forelse($previewRows as $row)
                <tr class="{{ $row['is_valid'] ? 'hover:bg-[#FAFDFE]' : 'bg-[#FDE8E8]/20 hover:bg-[#FDE8E8]/30' }} transition-colors">
                  <td class="py-3 px-4 text-center font-bold text-text-secondary">
                    {{ $row['row_number'] }}
                  </td>
                  <td class="py-3 px-4 text-center">
                    @if($row['is_valid'])
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-mint-soft text-status-success">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                        <span>Siap</span>
                      </span>
                    @else
                      <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-[#FDE8E8] text-status-error">
                        <span class="material-symbols-outlined text-[13px]">cancel</span>
                        <span>Gagal</span>
                      </span>
                    @endif
                  </td>
                  <td class="py-3 px-4">
                    <p class="font-extrabold text-on-surface">{{ $row['name'] ?: '-' }}</p>
                    <p class="text-[11px] text-text-secondary">NISN: {{ $row['nisn'] ?: '-' }}</p>
                  </td>
                  <td class="py-3 px-4">
                    <p class="font-bold text-on-surface">@<span>{{ $row['username'] }}</span></p>
                    <p class="text-[11px] text-text-secondary truncate">Pass: {{ $row['password'] }}</p>
                  </td>
                  <td class="py-3 px-4">
                    <p class="font-bold text-on-surface">{{ $row['kelas'] ?: '-' }}</p>
                    <p class="text-[11px] text-text-secondary truncate max-w-[160px]">{{ $row['jurusan'] ?: '-' }}</p>
                  </td>
                  <td class="py-3 px-4">
                    <p class="font-semibold text-on-surface truncate max-w-[150px]">{{ $row['tempat_pkl'] }}</p>
                    <p class="text-[11px] text-text-secondary truncate max-w-[150px]">Guru: {{ $row['guru_pembimbing'] }}</p>
                  </td>
                  <td class="py-3 px-4 text-[11px]">
                    @if(!empty($row['errors']))
                      <ul class="space-y-0.5 text-status-error font-bold">
                        @foreach($row['errors'] as $err)
                          <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">error</span>
                            <span>{{ $err }}</span>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                    @if(!empty($row['warnings']))
                      <ul class="space-y-0.5 text-status-warning font-semibold mt-1">
                        @foreach($row['warnings'] as $wrn)
                          <li class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[13px]">info</span>
                            <span>{{ $wrn }}</span>
                          </li>
                        @endforeach
                      </ul>
                    @endif
                    @if(empty($row['errors']) && empty($row['warnings']))
                      <span class="text-status-success font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">done</span>
                        <span>Data sesuai</span>
                      </span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="py-8 text-center text-text-secondary">
                    Tidak ada baris data yang ditemukan dalam berkas.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Action Confirmation Form -->
      <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-border-hairline">
        <p class="text-xs text-text-secondary font-medium">
          @if($summary['valid'] > 0)
            Hanya <strong class="text-on-surface">{{ $summary['valid'] }} akun valid</strong> yang akan disimpan. Baris yang bermasalah akan dilewati secara aman.
          @else
            <span class="text-status-error font-bold">Tidak ada akun yang valid untuk diimpor. Silakan perbaiki berkas CSV Anda.</span>
          @endif
        </p>

        <div class="flex items-center gap-3 w-full sm:w-auto">
          <a
            href="{{ route('admin.siswa.import') }}"
            class="w-full sm:w-auto px-4 py-3 rounded-xl border border-border-hairline hover:bg-gray-50 text-text-secondary font-bold text-xs transition-colors text-center"
          >
            Batal &amp; Unggah Ulang
          </a>

          <form action="{{ route('admin.siswa.import.confirm') }}" method="POST" class="w-full sm:w-auto">
            @csrf
            <button
              type="submit"
              {{ $summary['valid'] === 0 ? 'disabled' : '' }}
              class="w-full sm:w-auto px-6 py-3 rounded-xl bg-gradient-to-r from-[#00626D] to-[#0A8597] hover:from-[#00515A] hover:to-[#086F7E] disabled:opacity-40 disabled:cursor-not-allowed text-white font-extrabold text-xs shadow-md transition-all flex items-center justify-center gap-2"
            >
              <span class="material-symbols-outlined text-[18px]">person_add</span>
              <span>Konfirmasi &amp; Buat {{ $summary['valid'] }} Akun Siswa</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
  const fileInput = document.getElementById('fileImportInput');
  const fileBadge = document.getElementById('fileSelectedBadge');
  const fileName = document.getElementById('fileSelectedName');
  const filePrompt = document.getElementById('fileUploadPrompt');

  if (fileInput) {
    fileInput.addEventListener('change', (e) => {
      const file = e.target.files && e.target.files[0];
      if (file) {
        fileBadge.classList.remove('hidden');
        fileName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        filePrompt.textContent = 'Berkas dipilih, siap diperiksa';
      }
    });
  }
</script>
@endpush
