<?php
// File: app/Http/Controllers/SiswaKelolaController.php

namespace App\Http\Controllers;

use App\Models\TempatPkl;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaKelolaController extends Controller
{
    /**
     * Tampilkan daftar seluruh siswa dan penempatan PKL.
     */
    public function index(Request $request): View
    {
        $query = User::where('role', 'siswa')
            ->with(['tempatPkl', 'guruPembimbing']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tempat_pkl_id')) {
            $query->where('tempat_pkl_id', $request->get('tempat_pkl_id'));
        }

        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->get('guru_id'));
        }

        $siswaList = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        $tempatPklList = TempatPkl::orderBy('nama_perusahaan', 'asc')->get();
        $guruList = User::where('role', 'guru')->orderBy('name', 'asc')->get();

        return view('admin.siswa.index', compact('siswaList', 'tempatPklList', 'guruList'));
    }

    /**
     * Tampilkan formulir penambahan Siswa baru dan penempatannya.
     */
    public function create(): View
    {
        $tempatPklList = TempatPkl::orderBy('nama_perusahaan', 'asc')->get();
        $guruList = User::where('role', 'guru')->orderBy('name', 'asc')->get();

        return view('admin.siswa.form', [
            'siswa' => new User(),
            'isEdit' => false,
            'tempatPklList' => $tempatPklList,
            'guruList' => $guruList,
        ]);
    }

    /**
     * Simpan data Siswa baru ke dalam basis data.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nisn' => 'required|string|max:20|unique:users,nisn',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'tempat_pkl_id' => 'nullable|exists:tempat_pkl,id',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        $validated['role'] = 'siswa';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir edit data Siswa dan penempatan.
     */
    public function edit(int $id): View
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);
        $tempatPklList = TempatPkl::orderBy('nama_perusahaan', 'asc')->get();
        $guruList = User::where('role', 'guru')->orderBy('name', 'asc')->get();

        return view('admin.siswa.form', [
            'siswa' => $siswa,
            'isEdit' => true,
            'tempatPklList' => $tempatPklList,
            'guruList' => $guruList,
        ]);
    }

    /**
     * Perbarui data Siswa dalam basis data.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'nisn' => 'required|string|max:20|unique:users,nisn,' . $id,
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'tempat_pkl_id' => 'nullable|exists:tempat_pkl,id',
            'guru_id' => 'nullable|exists:users,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Hapus data Siswa beserta catatan aktivitas terkait.
     */
    public function destroy(int $id): RedirectResponse
    {
        $siswa = User::where('role', 'siswa')->findOrFail($id);

        // Hapus relasi riwayat aktivitas
        $siswa->absensi()->delete();
        $siswa->laporanHarian()->delete();
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Unduh berkas template spreadsheet (Excel .xls / CSV) untuk impor massal akun siswa.
     */
    public function downloadTemplate(Request $request): Response
    {
        $format = strtolower($request->query('format', 'excel'));

        if ($format === 'csv') {
            // Template format CSV (dengan titik-koma ';' agar Excel regional Indonesia langsung membuka kolom terpisah)
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            return response()->stream(function () {
                $handle = fopen('php://output', 'w');

                // Tuliskan UTF-8 Byte Order Mark (BOM)
                fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Header Kolom dengan pemisah titik-koma (standar Excel Indonesia)
                fputcsv($handle, [
                    'nama_lengkap',
                    'nisn',
                    'username',
                    'email',
                    'password',
                    'kelas',
                    'jurusan',
                    'tempat_pkl',
                    'guru_pembimbing',
                ], ';');

                // Baris Contoh Isian 1
                fputcsv($handle, [
                    'Ahmad Fauzi Pratama',
                    '0081234561',
                    'ahmad_fauzi',
                    'ahmad.fauzi@sekolah.sch.id',
                    'password123',
                    'XI TKJ 1',
                    'Teknik Komputer dan Jaringan',
                    'PT Telkom Indonesia',
                    'Budi Raharjo, S.Pd',
                ], ';');

                // Baris Contoh Isian 2
                fputcsv($handle, [
                    'Siti Nurhaliza',
                    '0081234562',
                    'siti_nurhaliza',
                    '',
                    'password123',
                    'XI RPL 1',
                    'Rekayasa Perangkat Lunak',
                    '',
                    '',
                ], ';');

                // Baris Contoh Isian 3
                fputcsv($handle, [
                    'Rian Hidayat',
                    '0081234563',
                    'rian_hidayat',
                    'rian@sekolah.sch.id',
                    'password123',
                    'XI TKJ 2',
                    'Teknik Komputer dan Jaringan',
                    '',
                    '',
                ], ';');

                fclose($handle);
            }, 200, $headers);
        }

        // Format Excel .xls murni (SpreadsheetML XML standar Microsoft Office)
        // Keuntungan: Excel langsung membuka kolom A-I terpisah, kolom rapi, font jelas, dan NISN diformat Teks (tidak hilang angka nol di depan).
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n"
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n"
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n"
            . ' <Styles>' . "\n"
            . '  <Style ss:ID="Default" ss:Name="Normal">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <Borders/>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#000000"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="HeaderCol">' . "\n"
            . '   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n"
            . '   <Borders>' . "\n"
            . '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#00626D"/>' . "\n"
            . '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#00626D"/>' . "\n"
            . '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#00626D"/>' . "\n"
            . '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#00626D"/>' . "\n"
            . '   </Borders>' . "\n"
            . '   <Font ss:FontName="Calibri" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>' . "\n"
            . '   <Interior ss:Color="#008294" ss:Pattern="Solid"/>' . "\n"
            . '  </Style>' . "\n"
            . '  <Style ss:ID="TextCell">' . "\n"
            . '   <Alignment ss:Vertical="Center"/>' . "\n"
            . '   <NumberFormat ss:Format="@"/>' . "\n"
            . '  </Style>' . "\n"
            . ' </Styles>' . "\n"
            . ' <Worksheet ss:Name="Data Siswa">' . "\n"
            . '  <Table>' . "\n"
            . '   <Column ss:Width="160"/>' . "\n"
            . '   <Column ss:Width="110"/>' . "\n"
            . '   <Column ss:Width="120"/>' . "\n"
            . '   <Column ss:Width="190"/>' . "\n"
            . '   <Column ss:Width="110"/>' . "\n"
            . '   <Column ss:Width="90"/>' . "\n"
            . '   <Column ss:Width="190"/>' . "\n"
            . '   <Column ss:Width="170"/>' . "\n"
            . '   <Column ss:Width="160"/>' . "\n"
            . '   <Row ss:Height="26">' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">nama_lengkap</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">nisn</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">username</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">email</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">password</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">kelas</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">jurusan</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">tempat_pkl</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="HeaderCol"><Data ss:Type="String">guru_pembimbing</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="20">' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Ahmad Fauzi Pratama</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">0081234561</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">ahmad_fauzi</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">ahmad.fauzi@sekolah.sch.id</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">password123</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">XI TKJ 1</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Teknik Komputer dan Jaringan</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">PT Telkom Indonesia</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Budi Raharjo, S.Pd</Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="20">' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Siti Nurhaliza</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">0081234562</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">siti_nurhaliza</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String"></Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">password123</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">XI RPL 1</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Rekayasa Perangkat Lunak</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String"></Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String"></Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '   <Row ss:Height="20">' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Rian Hidayat</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">0081234563</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">rian_hidayat</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">rian@sekolah.sch.id</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">password123</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">XI TKJ 2</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String">Teknik Komputer dan Jaringan</Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String"></Data></Cell>' . "\n"
            . '    <Cell ss:StyleID="TextCell"><Data ss:Type="String"></Data></Cell>' . "\n"
            . '   </Row>' . "\n"
            . '  </Table>' . "\n"
            . ' </Worksheet>' . "\n"
            . '</Workbook>';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Tampilkan formulir impor akun siswa secara massal.
     */
    public function importForm(): View
    {
        $tempatPklList = TempatPkl::orderBy('nama_perusahaan', 'asc')->get();
        $guruList = User::where('role', 'guru')->orderBy('name', 'asc')->get();

        return view('admin.siswa.import', [
            'tempatPklList' => $tempatPklList,
            'guruList' => $guruList,
            'previewRows' => null,
            'summary' => null,
        ]);
    }

    /**
     * Baca berkas spreadsheet (Excel .xls / CSV), validasi baris, dan tampilkan pratinjau konfirmasi.
     */
    public function importPreview(Request $request): View|RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'Silakan pilih berkas spreadsheet (Excel atau CSV) yang akan diimpor.',
            'file.file' => 'Berkas yang diunggah tidak valid.',
            'file.max' => 'Ukuran berkas maksimal adalah 5MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $content = file_get_contents($filePath);

        if ($content === false || trim($content) === '') {
            return redirect()->route('admin.siswa.import')
                ->with('error', 'Berkas yang diunggah kosong.');
        }

        $parsedRows = [];

        // 1. Deteksi Format XML Spreadsheet 2003 (SpreadsheetML .xls)
        if (str_contains($content, 'urn:schemas-microsoft-com:office:spreadsheet') || str_contains($content, '<Workbook')) {
            $xml = @simplexml_load_string($content);
            if ($xml !== false) {
                $xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
                $rows = $xml->xpath('//ss:Worksheet//ss:Table//ss:Row');

                foreach ($rows as $row) {
                    $row->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
                    $cells = $row->xpath('ss:Cell');
                    $rowData = [];
                    $colIdx = 0;

                    foreach ($cells as $cell) {
                        $attrs = $cell->attributes('urn:schemas-microsoft-com:office:spreadsheet');
                        if (isset($attrs['Index'])) {
                            $colIdx = ((int)$attrs['Index']) - 1;
                        }
                        $dataNodes = $cell->xpath('ss:Data');
                        $rowData[$colIdx] = !empty($dataNodes) ? trim((string)$dataNodes[0]) : '';
                        $colIdx++;
                    }

                    if (!empty($rowData)) {
                        $maxCol = max(array_keys($rowData));
                        $fullRow = [];
                        for ($c = 0; $c <= $maxCol; $c++) {
                            $fullRow[$c] = $rowData[$c] ?? '';
                        }
                        $parsedRows[] = $fullRow;
                    }
                }
            }
        }

        // 2. Jika bukan XML Spreadsheet, proses sebagai CSV / Teks Terstruktur
        if (empty($parsedRows)) {
            // Hapus UTF-8 BOM jika ada
            if (str_starts_with($content, "\xEF\xBB\xBF")) {
                $content = substr($content, 3);
            }

            // Pisahkan baris
            $lines = preg_split("/\r\n|\n|\r/", trim($content));
            if (empty($lines)) {
                return redirect()->route('admin.siswa.import')
                    ->with('error', 'Tidak ada baris data yang dapat dibaca dari berkas.');
            }

            // Deteksi pemisah kolom pada baris pertama (koma, titik-koma, atau tab)
            $firstLine = $lines[0];
            $commaCount = substr_count($firstLine, ',');
            $semiCount = substr_count($firstLine, ';');
            $tabCount = substr_count($firstLine, "\t");

            $delimiter = ',';
            if ($semiCount > $commaCount && $semiCount > $tabCount) {
                $delimiter = ';';
            } elseif ($tabCount > $commaCount && $tabCount > $semiCount) {
                $delimiter = "\t";
            }

            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                $parsedRows[] = str_getcsv($line, $delimiter);
            }
        }

        if (empty($parsedRows)) {
            return redirect()->route('admin.siswa.import')
                ->with('error', 'Tidak ada data tabel yang dapat dibaca dari berkas.');
        }

        // Baca baris header
        $headerRow = $parsedRows[0];
        $headerMap = [];
        foreach ($headerRow as $idx => $colName) {
            $cleaned = strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $colName))));
            $headerMap[$cleaned] = $idx;
        }

        // Pemetaan kolom kunci
        $colName = $headerMap['nama_lengkap'] ?? $headerMap['nama'] ?? $headerMap['name'] ?? null;
        $colNisn = $headerMap['nisn'] ?? null;
        $colUsername = $headerMap['username'] ?? $headerMap['user'] ?? null;
        $colEmail = $headerMap['email'] ?? null;
        $colPassword = $headerMap['password'] ?? $headerMap['pass'] ?? null;
        $colKelas = $headerMap['kelas'] ?? $headerMap['class'] ?? null;
        $colJurusan = $headerMap['jurusan'] ?? null;
        $colTempat = $headerMap['tempat_pkl'] ?? $headerMap['tempat'] ?? $headerMap['perusahaan'] ?? $headerMap['industri'] ?? null;
        $colGuru = $headerMap['guru_pembimbing'] ?? $headerMap['guru'] ?? $headerMap['pembimbing'] ?? null;

        if ($colName === null || $colNisn === null || $colKelas === null || $colJurusan === null) {
            return redirect()->route('admin.siswa.import')
                ->with('error', 'Format kolom berkas tidak sesuai. Pastikan berkas menggunakan template resmi (kolom nama_lengkap, nisn, kelas, dan jurusan wajib ada).');
        }

        // Preload data basis data untuk verifikasi cepat
        $existingNisns = User::pluck('nisn')->filter()->flip()->toArray();
        $existingUsernames = User::pluck('username')->map(fn($u) => strtolower($u))->flip()->toArray();
        $existingEmails = User::whereNotNull('email')->pluck('email')->map(fn($e) => strtolower($e))->flip()->toArray();
        $allTempatPkl = TempatPkl::all();
        $allGurus = User::where('role', 'guru')->get();

        $previewRows = [];
        $validRowsData = [];
        $fileNisns = [];
        $fileUsernames = [];
        $fileEmails = [];

        $totalDataRows = 0;
        $validCount = 0;
        $invalidCount = 0;

        // Baca setiap baris data (mulai baris kedua)
        for ($i = 1; $i < count($parsedRows); $i++) {
            $row = $parsedRows[$i];

            // Cek apakah seluruh baris kosong
            $allEmpty = true;
            foreach ($row as $val) {
                if (trim($val) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            $totalDataRows++;

            $name = isset($colName, $row[$colName]) ? trim($row[$colName]) : '';
            $nisn = isset($colNisn, $row[$colNisn]) ? trim($row[$colNisn]) : '';
            $username = isset($colUsername, $row[$colUsername]) ? trim($row[$colUsername]) : '';
            $email = isset($colEmail, $row[$colEmail]) ? trim($row[$colEmail]) : '';
            $password = isset($colPassword, $row[$colPassword]) ? trim($row[$colPassword]) : '';
            $kelas = isset($colKelas, $row[$colKelas]) ? trim($row[$colKelas]) : '';
            $jurusan = isset($colJurusan, $row[$colJurusan]) ? trim($row[$colJurusan]) : '';
            $tempatRaw = isset($colTempat, $row[$colTempat]) ? trim($row[$colTempat]) : '';
            $guruRaw = isset($colGuru, $row[$colGuru]) ? trim($row[$colGuru]) : '';

            $errors = [];
            $warnings = [];

            // 1. Validasi Nama
            if ($name === '') {
                $errors[] = 'Nama lengkap wajib diisi.';
            }

            // 2. Validasi NISN
            if ($nisn === '') {
                $errors[] = 'NISN wajib diisi.';
            } else {
                if (isset($existingNisns[$nisn])) {
                    $errors[] = "NISN '{$nisn}' sudah terdaftar di sistem.";
                } elseif (isset($fileNisns[$nisn])) {
                    $errors[] = "NISN '{$nisn}' terduplikasi di dalam berkas (baris {$fileNisns[$nisn]}).";
                } else {
                    $fileNisns[$nisn] = $i + 1;
                }
            }

            // 3. Validasi & Auto-generate Username
            if ($username === '') {
                $username = !empty($nisn) ? $nisn : strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', Str::slug($name, '_')));
            }
            $usernameLower = strtolower($username);
            if (isset($existingUsernames[$usernameLower])) {
                $errors[] = "Username '{$username}' sudah digunakan akun lain.";
            } elseif (isset($fileUsernames[$usernameLower])) {
                $errors[] = "Username '{$username}' terduplikasi di dalam berkas.";
            } else {
                $fileUsernames[$usernameLower] = $i + 1;
            }

            // 4. Validasi Email (Opsional)
            if ($email !== '') {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Format email '{$email}' tidak valid.";
                } else {
                    $emailLower = strtolower($email);
                    if (isset($existingEmails[$emailLower])) {
                        $errors[] = "Email '{$email}' sudah terdaftar di sistem.";
                    } elseif (isset($fileEmails[$emailLower])) {
                        $errors[] = "Email '{$email}' terduplikasi di dalam berkas.";
                    } else {
                        $fileEmails[$emailLower] = $i + 1;
                    }
                }
            }

            // 5. Validasi Password
            if ($password === '') {
                $password = 'password123';
                $warnings[] = 'Password kosong, akan menggunakan default: password123';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Password minimal terdiri dari 6 karakter.';
            }

            // 6. Validasi Kelas & Jurusan
            if ($kelas === '') {
                $errors[] = 'Kelas wajib diisi.';
            }
            if ($jurusan === '') {
                $errors[] = 'Jurusan wajib diisi.';
            }

            // 7. Resolusi Tempat PKL (Opsional)
            $tempatPklId = null;
            $tempatPklNama = '-';
            if ($tempatRaw !== '') {
                if (is_numeric($tempatRaw)) {
                    $foundTempat = $allTempatPkl->firstWhere('id', (int)$tempatRaw);
                } else {
                    $foundTempat = $allTempatPkl->first(function ($item) use ($tempatRaw) {
                        return strcasecmp($item->nama_perusahaan, $tempatRaw) === 0
                            || stripos($item->nama_perusahaan, $tempatRaw) !== false;
                    });
                }

                if ($foundTempat) {
                    $tempatPklId = $foundTempat->id;
                    $tempatPklNama = $foundTempat->nama_perusahaan;
                } else {
                    $warnings[] = "Tempat PKL '{$tempatRaw}' tidak ditemukan, siswa belum ditempatkan.";
                }
            }

            // 8. Resolusi Guru Pembimbing (Opsional)
            $guruId = null;
            $guruNama = '-';
            if ($guruRaw !== '') {
                if (is_numeric($guruRaw)) {
                    $foundGuru = $allGurus->firstWhere('id', (int)$guruRaw);
                } else {
                    $foundGuru = $allGurus->first(function ($item) use ($guruRaw) {
                        return strcasecmp($item->name, $guruRaw) === 0
                            || strcasecmp($item->username, $guruRaw) === 0
                            || stripos($item->name, $guruRaw) !== false;
                    });
                }

                if ($foundGuru) {
                    $guruId = $foundGuru->id;
                    $guruNama = $foundGuru->name;
                } else {
                    $warnings[] = "Guru '{$guruRaw}' tidak ditemukan, alokasi pembimbing dikosongkan.";
                }
            }

            $isValid = count($errors) === 0;
            if ($isValid) {
                $validCount++;
                $validRowsData[] = [
                    'name' => $name,
                    'username' => $username,
                    'email' => $email ?: null,
                    'password' => $password,
                    'nisn' => $nisn,
                    'kelas' => $kelas,
                    'jurusan' => $jurusan,
                    'tempat_pkl_id' => $tempatPklId,
                    'guru_id' => $guruId,
                ];
            } else {
                $invalidCount++;
            }

            $previewRows[] = [
                'row_number' => $i,
                'name' => $name,
                'nisn' => $nisn,
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'kelas' => $kelas,
                'jurusan' => $jurusan,
                'tempat_pkl' => $tempatPklNama,
                'guru_pembimbing' => $guruNama,
                'is_valid' => $isValid,
                'errors' => $errors,
                'warnings' => $warnings,
            ];
        }

        // Simpan data baris valid ke dalam session untuk dieksekusi saat konfirmasi
        session(['pending_siswa_import' => $validRowsData]);

        $summary = [
            'total' => $totalDataRows,
            'valid' => $validCount,
            'invalid' => $invalidCount,
        ];

        $tempatPklList = $allTempatPkl;
        $guruList = $allGurus;

        return view('admin.siswa.import', compact('previewRows', 'summary', 'tempatPklList', 'guruList'));
    }

    /**
     * Konfirmasi dan eksekusi pembuatan akun siswa massal dari data yang telah divalidasi.
     */
    public function importConfirm(Request $request): RedirectResponse
    {
        $pendingData = session('pending_siswa_import');

        if (empty($pendingData) || !is_array($pendingData)) {
            return redirect()->route('admin.siswa.import')
                ->with('error', 'Sesi data impor telah kedaluwarsa atau belum ada berkas yang divalidasi. Silakan unggah berkas kembali.');
        }

        $createdCount = 0;

        DB::transaction(function () use ($pendingData, &$createdCount) {
            foreach ($pendingData as $item) {
                // Verifikasi akhir untuk memastikan tidak ada konflik unik
                if (User::where('username', $item['username'])->exists() || User::where('nisn', $item['nisn'])->exists()) {
                    continue;
                }

                User::create([
                    'name' => $item['name'],
                    'username' => $item['username'],
                    'email' => $item['email'],
                    'password' => Hash::make($item['password']),
                    'role' => 'siswa',
                    'nisn' => $item['nisn'],
                    'kelas' => $item['kelas'],
                    'jurusan' => $item['jurusan'],
                    'tempat_pkl_id' => $item['tempat_pkl_id'],
                    'guru_id' => $item['guru_id'],
                ]);

                $createdCount++;
            }
        });

        // Bersihkan sesi impor
        session()->forget('pending_siswa_import');

        return redirect()->route('admin.siswa.index')
            ->with('success', "Berhasil membuat {$createdCount} akun siswa secara massal melalui impor spreadsheet.");
    }
}
