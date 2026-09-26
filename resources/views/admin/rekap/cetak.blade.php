<!-- File: resources/views/admin/rekap/cetak.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Cetak Rekap Presensi & Jurnal PKL — SMK Plus Pelita Nusantara</title>
  <link rel="icon" type="image/png" href="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Nunito Sans', sans-serif;
      color: #111827;
      background-color: #f3f4f6;
    }
    @media print {
      body {
        background-color: #ffffff;
      }
      .no-print {
        display: none !important;
      }
      .print-container {
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        max-width: 100% !important;
      }
      table {
        page-break-inside: auto;
      }
      tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
    }
  </style>
</head>
<body class="py-8 px-4">
  <!-- Top Print Toolbar (Screen Only) -->
  <div class="max-w-5xl mx-auto mb-6 flex items-center justify-between no-print">
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.rekap.index') }}" class="px-4 py-2 rounded-xl bg-white border border-gray-300 text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors">
        Kembali ke Rekap
      </a>
      <span class="text-xs text-gray-500">Pratinjau Dokumen Cetak Format A4</span>
    </div>
    <button onclick="window.print()" class="px-5 py-2 rounded-xl bg-[#008294] text-white font-bold text-xs hover:bg-[#006e7d] transition-colors shadow-sm flex items-center gap-1.5">
      <span>Cetak / Simpan PDF</span>
    </button>
  </div>

  <!-- Sheet Container -->
  <div class="max-w-5xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-200 print-container">
    <!-- Kop Surat Sekolah -->
    <div class="flex items-center gap-6 pb-4 border-b-2 border-gray-900">
      <div class="w-20 h-20 shrink-0">
        <img src="{{ asset('Logo1.png') }}?v={{ file_exists(public_path('Logo1.png')) ? filemtime(public_path('Logo1.png')) : '1.0' }}" alt="Logo SMK Plus Pelita Nusantara" class="w-full h-full object-contain">
      </div>
      <div class="flex-1 text-center">
        <h2 class="text-xs font-bold tracking-widest uppercase text-gray-600">Yayasan Pelita Nusantara</h2>
        <h1 class="text-xl font-black text-gray-900 tracking-tight uppercase">SMK Plus Pelita Nusantara</h1>
        <p class="text-xs text-gray-700 font-semibold mt-0.5">Bidang Keahlian: Teknologi Informasi dan Komunikasi &bull; Bisnis dan Manajemen</p>
        <p class="text-[11px] text-gray-500 mt-0.5">Jl. Golf No. 1, Ciriung, Cibinong, Kabupaten Bogor, Jawa Barat | Website: smkpluspnbogor.sch.id</p>
      </div>
    </div>

    <!-- Judul Dokumen & Periode -->
    <div class="text-center my-6">
      <h3 class="text-base font-extrabold text-gray-900 tracking-wider uppercase underline underline-offset-4">
        Rekapitulasi Kehadiran & Jurnal Harian Siswa PKL
      </h3>
      <p class="text-xs text-gray-600 mt-2 font-semibold">
        Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}
      </p>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border border-gray-300 border-collapse">
        <thead>
          <tr class="bg-gray-100 text-gray-800 font-bold border-b border-gray-300">
            <th class="py-2.5 px-3 border-r border-gray-300 text-center w-8">No</th>
            <th class="py-2.5 px-3 border-r border-gray-300 w-24">Tanggal & Jam</th>
            <th class="py-2.5 px-3 border-r border-gray-300">Nama Siswa & NISN</th>
            <th class="py-2.5 px-3 border-r border-gray-300">Kelas / Jurusan</th>
            <th class="py-2.5 px-3 border-r border-gray-300">Tempat PKL Mitra</th>
            <th class="py-2.5 px-3 border-r border-gray-300 text-center w-16">Status</th>
            <th class="py-2.5 px-3">Rencana Tugas / Jurnal Kerja</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse($records as $index => $item)
            <tr class="align-top">
              <td class="py-2 px-3 border-r border-gray-300 text-center font-medium">{{ $index + 1 }}</td>
              <td class="py-2 px-3 border-r border-gray-300 text-gray-700">
                <span class="font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
                <br>
                <span class="text-[11px] text-gray-500">{{ $item->jam ?? '-' }} WIB</span>
              </td>
              <td class="py-2 px-3 border-r border-gray-300">
                <span class="font-bold text-gray-900">{{ $item->siswa->name ?? '-' }}</span>
                <br>
                <span class="text-[11px] text-gray-600">NISN: {{ $item->siswa->nisn ?? '-' }}</span>
              </td>
              <td class="py-2 px-3 border-r border-gray-300 text-gray-700">
                <span class="font-semibold">{{ $item->siswa->kelas ?? '-' }}</span>
                <br>
                <span class="text-[10px] text-gray-500">{{ $item->siswa->jurusan ?? '-' }}</span>
              </td>
              <td class="py-2 px-3 border-r border-gray-300 text-gray-700 font-medium">
                {{ $item->siswa->tempatPkl->nama_perusahaan ?? '-' }}
              </td>
              <td class="py-2 px-3 border-r border-gray-300 text-center font-bold">
                <span class="uppercase text-[11px] {{ $item->status === 'hadir' ? 'text-emerald-700' : 'text-amber-700' }}">
                  {{ $item->status ?? 'hadir' }}
                </span>
              </td>
              <td class="py-2 px-3 text-gray-800">
                @if($item->laporan)
                  <p class="font-medium leading-relaxed">{{ $item->laporan->rencana_tugas }}</p>
                  @if($item->laporan->catatan)
                    <p class="text-[10px] text-gray-500 italic mt-0.5">Catatan: {{ $item->laporan->catatan }}</p>
                  @endif
                @else
                  <span class="text-gray-400 italic">Belum mengisi laporan harian</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-gray-500">
                Tidak ada data aktivitas siswa pada rentang tanggal tersebut.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Lembar Tanda Tangan Pengesahan -->
    <div class="mt-12 pt-6 grid grid-cols-2 text-center text-xs">
      <div>
        <p class="text-gray-600">Mengetahui,</p>
        <p class="font-bold text-gray-900 mt-1">Koordinator Hubungan Industri (Hubin)</p>
        <div class="h-20"></div>
        <p class="font-bold underline text-gray-900">{{ $pembimbing->name ?? 'Bapak Andi Pratama, S.Pd.' }}</p>
        <p class="text-[11px] text-gray-600">NIP: {{ $pembimbing->nip ?? '198503152010011002' }}</p>
      </div>

      <div>
        <p class="text-gray-600">Bogor, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        <p class="font-bold text-gray-900 mt-1">Kepala SMK Plus Pelita Nusantara</p>
        <div class="h-20"></div>
        <p class="font-bold underline text-gray-900">Drs. H. M. Kosasih, M.M.</p>
        <p class="text-[11px] text-gray-600">NIP: 196805121994031005</p>
      </div>
    </div>
  </div>
</body>
</html>
