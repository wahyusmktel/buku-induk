<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\PrestasiBelajar;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

class BukuIndukController extends Controller
{
    /**
     * Index: List all buku induk records, searchable by name or NISN.
     */
    public function index(Request $request)
    {
        $search   = $request->get('q', '');
        $tingkat  = $request->get('tingkat', '');
        $rombelId = $request->get('rombel_id', '');
        $perPage  = (int) $request->get('per_page', 20);

        // Clamp per_page to allowed values
        $allowedPerPage = [10, 20, 30, 40, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }

        // Get active tahun pelajaran
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();

        // Get available tingkat & rombel for filters (from siswa in active tahun)
        $tingkatList = collect();
        $rombelList  = collect();
        if ($tahunAktif) {
            $tingkatList = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)
                ->whereNotNull('tingkat_kelas')
                ->distinct()
                ->orderBy('tingkat_kelas')
                ->pluck('tingkat_kelas');

            $rombelQuery = \App\Models\Rombel::orderBy('nama');
            $rombelList  = $rombelQuery->get();
        }

        // Build query: siswa in active tahun pelajaran
        $query = Siswa::query()->whereNotNull('nisn');

        if ($tahunAktif) {
            $query->where('tahun_pelajaran_id', $tahunAktif->id);
        }

        if ($tingkat) {
            $query->where('tingkat_kelas', $tingkat);
        }

        if ($rombelId) {
            $query->where('rombel_id', $rombelId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('tingkat_kelas', 'asc')
            ->orderBy('nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Map nisn => BukuInduk record (with kelengkapan accessor)
        $nisnList     = $siswas->pluck('nisn')->filter()->toArray();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisnList)->get()->keyBy('nisn');

        // Eager-load related data for kelengkapan calculation
        $siswaWithRelations = Siswa::whereIn('nisn', $nisnList)
            ->where('tahun_pelajaran_id', $tahunAktif?->id)
            ->with(['dataPeriodik', 'keadaanJasmani', 'dataOrangTua'])
            ->get()
            ->keyBy('nisn');

        foreach ($bukuIndukMap as $nisn => $bi) {
            $bi->setRelation('siswaPokok', $siswaWithRelations[$nisn] ?? null);
        }

        return view('buku-induk.index', compact(
            'siswas', 'bukuIndukMap', 'search', 'perPage',
            'tahunAktif', 'tingkatList', 'rombelList', 'tingkat', 'rombelId'
        ));
    }

    /**
     * Show: Display the full Buku Induk for a student.
     */
    public function show(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        // Fetch the canonical siswa record (latest) with relations for kelengkapan
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->with(['dataPeriodik', 'keadaanJasmani', 'dataOrangTua'])
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $bukuInduk->setRelation('siswaPokok', $siswa);
        $kelengkapan = $bukuInduk->kelengkapan;

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        // Build academic grid: dynamic grades based on recorded data
        $availableGrades = $prestasis->pluck('kelas')->unique()->sort()->values()->toArray();
        if (empty($availableGrades)) {
            $availableGrades = [1]; 
        }

        $akademikGrid = [];
        foreach ($availableGrades as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        $ekstrakurikulers = \App\Models\Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

        $activeTahunPelajaran = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        $siswaActive = \App\Models\Siswa::where('nisn', $bukuInduk->nisn)->where('tahun_pelajaran_id', $activeTahunPelajaran?->id)->first();
        $currentRombel = $siswaActive ? $siswaActive->rombel : null;

        return view('buku-induk.show', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'kelengkapan', 'ekstrakurikulers', 'activeTahunPelajaran', 'currentRombel'));
    }

    /**
     * Edit: Show the edit form.
     */
    public function edit(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->with(['dataPeriodik', 'keadaanJasmani', 'dataOrangTua'])
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $bukuInduk->setRelation('siswaPokok', $siswa);
        $kelengkapan = $bukuInduk->kelengkapan;

        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();
        $jenjang = \App\Models\Setting::where('key', 'jenjang_pendidikan')->first()?->value ?? 'SD';
        $ekstrakurikulers = \App\Models\Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

        $activeTahunPelajaran = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        $siswaActive = \App\Models\Siswa::where('nisn', $nisn)->where('tahun_pelajaran_id', $activeTahunPelajaran?->id)->first();
        $currentRombel = $siswaActive ? $siswaActive->rombel : null;

        $activeSmtInt = strtolower($activeTahunPelajaran?->semester ?? '') == 'ganjil' ? 1 : 2;
        $activePrestasi = null;
        if ($currentRombel && $activeTahunPelajaran) {
            $activePrestasi = $bukuInduk->prestasis()
                ->where('kelas', $currentRombel->tingkat)
                ->where('semester', $activeSmtInt)
                ->first();
        }

        // Data ekskul aktif untuk pre-populate modal ekskul
        $aktifEkskuls = collect();
        if ($currentRombel && $activeTahunPelajaran && $siswaActive) {
            $aktifEkskuls = \App\Models\PrestasiEkstrakurikuler::where('siswa_id', $siswaActive->id)
                ->where('kelas', $currentRombel->tingkat)
                ->where('semester', $activeSmtInt)
                ->get()
                ->keyBy('ekstrakurikuler_id');
        }

        // Semua data ekskul per semester (untuk ringkasan di tab)
        $allEkskulPrestasi = collect();
        if ($siswaActive) {
            $allEkskulPrestasi = \App\Models\PrestasiEkstrakurikuler::where('siswa_id', $siswaActive->id)
                ->with('ekstrakurikuler')
                ->orderBy('kelas')->orderBy('semester')
                ->get()
                ->groupBy(fn($e) => "Kelas {$e->kelas} Smt {$e->semester}");
        }

        return view('buku-induk.edit', compact('bukuInduk', 'siswa', 'mataPelajarans', 'jenjang', 'kelengkapan', 'ekstrakurikulers', 'activeTahunPelajaran', 'currentRombel', 'activePrestasi', 'aktifEkskuls', 'allEkskulPrestasi', 'siswaActive'));
    }

    /**
     * Update: Save buku induk fields.
     */
    public function update(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        // Validasi yang disesuaikan dengan struktur tab yang baru (bersifat dinamis)
        $validated = $request->validate([
            'nis' => 'nullable|string',
            'nisn' => 'nullable|string',
            'nik' => 'nullable|string',
            'nama' => 'nullable|string',
            'nama_panggilan' => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string',
            'kewarganegaraan' => 'nullable|string',
            'telepon' => 'nullable|string',

            'ayah' => 'nullable|array',
            'ibu' => 'nullable|array',
            'wali' => 'nullable|array',

            'periodik' => 'nullable|array',
            'jasmani' => 'nullable|array',
            'beasiswa' => 'nullable|array',
            'registrasi' => 'nullable|array',
            'tamat' => 'nullable|array',
            'pendidikan_sebelumnya' => 'nullable|array',

            'foto_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated, $siswa, $bukuInduk, $nisn) {
            
            // 1. Update Siswa Induk
            $siswa->update([
                'nipd' => $validated['nis'] ?? $siswa->nipd,
                'nisn' => $validated['nisn'] ?? $siswa->nisn,
                'nik' => $validated['nik'] ?? $siswa->nik,
                'nama' => $validated['nama'] ?? $siswa->nama,
                'nama_panggilan' => $validated['nama_panggilan'] ?? $siswa->nama_panggilan,
                'jk' => $validated['jenis_kelamin'] ?? $siswa->jk,
                'tempat_lahir' => $validated['tempat_lahir'] ?? $siswa->tempat_lahir,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? $siswa->tanggal_lahir,
                'agama' => $validated['agama'] ?? $siswa->agama,
                'kewarganegaraan' => $validated['kewarganegaraan'] ?? $siswa->kewarganegaraan,
                'telepon' => $validated['telepon'] ?? $siswa->telepon,
            ]);

            // 2. Update Data Orang Tua (Ayah, Ibu, Wali)
            if (isset($validated['ayah'])) {
                \App\Models\DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Ayah'],
                    [
                        'nama' => $validated['ayah']['nama'] ?? null,
                        'pendidikan_terakhir' => $validated['ayah']['pendidikan'] ?? null,
                        'pekerjaan' => $validated['ayah']['pekerjaan'] ?? null,
                    ]
                );
            }
            if (isset($validated['ibu'])) {
                \App\Models\DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Ibu'],
                    [
                        'nama' => $validated['ibu']['nama'] ?? null,
                        'pendidikan_terakhir' => $validated['ibu']['pendidikan'] ?? null,
                        'pekerjaan' => $validated['ibu']['pekerjaan'] ?? null,
                    ]
                );
            }
            if (isset($validated['wali'])) {
                \App\Models\DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Wali'],
                    [
                        'nama' => $validated['wali']['nama'] ?? null,
                        'status_hubungan_wali' => $validated['wali']['hubungan'] ?? null,
                        'pendidikan_terakhir' => $validated['wali']['pendidikan'] ?? null,
                        'pekerjaan' => $validated['wali']['pekerjaan'] ?? null,
                    ]
                );
            }

            // 3. Update Data Periodik
            if (isset($validated['periodik'])) {
                \App\Models\DataPeriodikSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id],
                    [
                        'jml_saudara_kandung' => $validated['periodik']['jml_saudara_kandung'] ?? 0,
                        'jml_saudara_tiri' => $validated['periodik']['jml_saudara_tiri'] ?? 0,
                        'jml_saudara_angkat' => $validated['periodik']['jml_saudara_angkat'] ?? 0,
                        'bahasa_sehari_hari' => $validated['periodik']['bahasa_sehari_hari'] ?? null,
                        'alamat_tinggal' => $validated['periodik']['alamat_tinggal'] ?? null,
                        'bertempat_tinggal_pada' => $validated['periodik']['bertempat_tinggal_pada'] ?? null,
                        'jarak_tempat_tinggal_ke_sekolah' => $validated['periodik']['jarak'] ?? null,
                    ]
                );
            }

            // 4. Update Keadaan Jasmani
            if (isset($validated['jasmani'])) {
                \App\Models\KeadaanJasmaniSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id],
                    [
                        'berat_badan' => $validated['jasmani']['berat_badan'] ?? null,
                        'tinggi_badan' => $validated['jasmani']['tinggi_badan'] ?? null,
                        'golongan_darah' => $validated['jasmani']['golongan_darah'] ?? null,
                        'nama_riwayat_penyakit' => $validated['jasmani']['riwayat_penyakit'] ?? null,
                        'kelainan_jasmani' => $validated['jasmani']['kelainan'] ?? null,
                    ]
                );
            }

            // 5. Update Beasiswa (Replace All)
            if (isset($validated['beasiswa']) && is_array($validated['beasiswa'])) {
                $siswa->beasiswa()->delete(); // Bersihkan yang lama
                $beasiswaRecords = [];
                foreach ($validated['beasiswa'] as $item) {
                    if (!empty($item['jenis_beasiswa'])) {
                        $beasiswaRecords[] = [
                            'id' => \Illuminate\Support\Str::uuid(),
                            'siswa_id' => $siswa->id,
                            'jenis_beasiswa' => $item['jenis_beasiswa'],
                            'sumber_beasiswa' => $item['sumber_beasiswa'] ?? null,
                            'tahun_mulai' => $item['tahun_mulai'] ?? null,
                            'tahun_selesai' => $item['tahun_selesai'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                if (count($beasiswaRecords) > 0) {
                    \App\Models\BeasiswaSiswa::insert($beasiswaRecords);
                }
            } else {
                $siswa->beasiswa()->delete();
            }

            // 6. Update Registrasi (Replace All)
            if (isset($validated['registrasi']) && is_array($validated['registrasi'])) {
                $siswa->registrasi()->delete();
                $registrasiRecords = [];
                foreach ($validated['registrasi'] as $item) {
                    if (!empty($item['jenis_registrasi'])) {
                        $registrasiRecords[] = [
                            'id' => \Illuminate\Support\Str::uuid(),
                            'siswa_id' => $siswa->id,
                            'jenis_registrasi' => $item['jenis_registrasi'],
                            'tanggal' => $item['tanggal'] ?? null,
                            'keterangan' => $item['keterangan'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                if (count($registrasiRecords) > 0) {
                    \App\Models\RegistrasiSiswa::insert($registrasiRecords);
                }
            } else {
                $siswa->registrasi()->delete();
            }

            // 7. Proses Foto
            // 7. Update Pendidikan Sebelumnya (ke tabel buku_induks)
            if (isset($validated['pendidikan_sebelumnya'])) {
                $ps = $validated['pendidikan_sebelumnya'];
                $bukuInduk->fill([
                    'asal_masuk_sekolah' => $ps['asal_siswa'] ?? null,
                    'nama_tk_asal' => $ps['nama_sekolah_asal'] ?? null,
                    'tgl_masuk_sekolah' => $ps['tgl_masuk'] ?? null,
                    'pindah_dari' => $ps['pindah_nama_sekolah'] ?? null,
                    'kelas_pindah_masuk' => $ps['pindah_di_kelas'] ?? null,
                    'tgl_pindah_masuk' => $ps['pindah_tgl_diterima'] ?? null,
                ]);
            }

            if (isset($validated['tamat'])) {
                $bukuInduk->fill([
                    'tgl_lulus' => $validated['tamat']['tgl_lulus'] ?? null,
                    'no_ijazah' => $validated['tamat']['no_ijazah'] ?? null,
                    'tanggal_ijazah' => $validated['tamat']['tanggal_ijazah'] ?? null,
                    'lanjut_ke' => $validated['tamat']['lanjut_ke'] ?? null,
                ]);
            }

            // 8. Proses Foto
            if ($request->hasFile('foto_1')) {
                if ($bukuInduk->foto_1) Storage::disk('public')->delete($bukuInduk->foto_1);
                $file = $request->file('foto_1');
                $filename = time() . '_1_' . $file->getClientOriginalName();
                $file->move(storage_path('app/public/siswa_photos'), $filename);
                $bukuInduk->foto_1 = 'siswa_photos/' . $filename;
            }
            
            if ($request->hasFile('foto_2')) {
                if ($bukuInduk->foto_2) Storage::disk('public')->delete($bukuInduk->foto_2);
                $file = $request->file('foto_2');
                $filename = time() . '_2_' . $file->getClientOriginalName();
                $file->move(storage_path('app/public/siswa_photos'), $filename);
                $bukuInduk->foto_2 = 'siswa_photos/' . $filename;
            }
            
            $bukuInduk->save();
        });

        // 8. Log Activity
        $namaSiswa = $siswa->nama ?? "NISN: {$nisn}";
        ActivityLogService::log('buku_induk_update', "Melengkapi data Buku Induk: {$namaSiswa}", [
            'nisn' => $nisn,
            'nama' => $namaSiswa
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil dan aman diperbarui ke seluruh tabel relasi.');
    }

    /**
     * Print: Render a printable, PDF-ready view.
     */
    public function print(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->with(['dataPeriodik', 'keadaanJasmani', 'dataOrangTua', 'beasiswa', 'registrasi', 'tahunPelajaran'])
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        $availableGrades = $prestasis->pluck('kelas')->unique()->sort()->values()->toArray();
        if (empty($availableGrades)) {
            $availableGrades = [1];
        }

        $akademikGrid = [];
        // Always build for all 6 kelas so the transposed table renders all columns
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $ekstrakurikulers = \App\Models\Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

        // Pre-process gambar via GD agar DomPDF bisa render berwarna (fix PNG grayscale bug)
        $imageKeys = ['sekolah_kop', 'kepsek_ttd', 'sekolah_stempel'];
        $tempDir = storage_path('app/public/settings/_pdf_temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        foreach ($imageKeys as $key) {
            if (!empty($settings[$key])) {
                $srcPath = storage_path('app/public/' . $settings[$key]);
                if (file_exists($srcPath)) {
                    $ext = strtolower(pathinfo($srcPath, PATHINFO_EXTENSION));
                    $src = null;
                    if ($ext === 'png') {
                        $src = @imagecreatefrompng($srcPath);
                    } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                        $src = @imagecreatefromjpeg($srcPath);
                    }
                    if ($src) {
                        $w = imagesx($src);
                        $h = imagesy($src);
                        $canvas = imagecreatetruecolor($w, $h);
                        $white = imagecolorallocate($canvas, 255, 255, 255);
                        imagefill($canvas, 0, 0, $white);
                        imagecopy($canvas, $src, 0, 0, 0, 0, $w, $h);

                        $cleanPath = $tempDir . '/' . $key . '.png';
                        imagepng($canvas, $cleanPath, 0);
                        imagedestroy($src);
                        imagedestroy($canvas);

                        // Simpan path absolut file bersih ke settings
                        $settings[$key . '_pdf'] = $cleanPath;
                    }
                }
            }
        }

        $is_pdf = true;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('buku-induk.print', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings', 'ekstrakurikulers', 'is_pdf'))
            ->setOption('isPhpEnabled', true);
        
        $paperSize = $settings['paper_size'] ?? 'a4';
        
        if ($paperSize === 'custom') {
            // Konversi dari mm ke default unit PDF (points). 1 mm = 2.83465 pt
            $width = ($settings['paper_width'] ?? 210) * 2.83465;
            $height = ($settings['paper_height'] ?? 297) * 2.83465;
            $pdf->setPaper([0, 0, $width, $height], 'portrait');
        } elseif ($paperSize === 'folio') {
            // F4 / Folio (215.9mm x 330.2mm)
            $pdf->setPaper([0, 0, 612.0, 936.0], 'portrait');
        } else {
            // a4, legal, letter sudah dikenali dari native DomPDF
            $pdf->setPaper($paperSize, 'portrait');
        }

        return $pdf->stream("Buku_Induk_{$nisn}_{$siswa->nama}.pdf");
    }

    public function printPrestasi($nisn)
    {
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')->where('nisn', $nisn)->firstOrFail();
        $bukuInduk = BukuInduk::firstOrCreate(
            ['nisn' => $nisn],
            ['siswa_id' => $siswa->id]
        );

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        $akademikGrid = [];
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $ekstrakurikulers = \App\Models\Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

        $is_pdf = true;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('buku-induk.print-prestasi', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings', 'ekstrakurikulers', 'is_pdf'))
            ->setOption('isPhpEnabled', true);
        
        $paperSize = $settings['paper_size'] ?? 'a4';
        
        // Memaksa orientasi LANDSCAPE
        if ($paperSize === 'custom') {
            $width = ($settings['paper_width'] ?? 210) * 2.83465;
            $height = ($settings['paper_height'] ?? 297) * 2.83465;
            $pdf->setPaper([0, 0, $width, $height], 'landscape');
        } elseif ($paperSize === 'folio') {
            $pdf->setPaper([0, 0, 612.0, 936.0], 'landscape');
        } else {
            $pdf->setPaper($paperSize, 'landscape');
        }

        return $pdf->stream("Prestasi_Belajar_{$nisn}_{$siswa->nama}.pdf");
    }
}
