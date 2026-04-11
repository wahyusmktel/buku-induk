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
        $search       = $request->get('q', '');
        $statusFilter = $request->get('status', 'Aktif');
        $tahunId      = $request->get('tahun_id', '');
        $angkatan     = $request->get('angkatan', '');
        $perPage      = (int) $request->get('per_page', 20);

        // Clamp per_page to allowed values
        $allowedPerPage = [10, 20, 30, 40, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }

        // All tahun pelajaran for the filter dropdown
        $tahunPelajarans = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();
        // Unique angkatan for filter
        $angkatans = Siswa::withoutGlobalScope('tahun_aktif')
            ->whereNotNull('tahun_masuk')
            ->distinct()
            ->orderByDesc('tahun_masuk')
            ->pluck('tahun_masuk');

        // ── Build query: satu baris per NISN (terbaru by created_at) ─────
        // UUID primary key tidak bisa di-MAX(), jadi kita pakai MAX(created_at).
        // Subquery menghasilkan: nisn → max created_at dalam scope tahun (jika dipilih).

        $latestSub = Siswa::withoutGlobalScope('tahun_aktif')
            ->selectRaw('nisn AS s_nisn, MAX(created_at) AS max_ca')
            ->whereNotNull('nisn');

        if ($tahunId) {
            $latestSub->where('tahun_pelajaran_id', $tahunId);
        }

        $latestSub->groupBy('nisn');

        $query = Siswa::withoutGlobalScope('tahun_aktif')
            ->joinSub($latestSub, 'ls', function ($join) {
                $join->on('siswas.nisn', '=', 'ls.s_nisn')
                     ->on('siswas.created_at', '=', 'ls.max_ca');
            })
            ->select(
                'siswas.nisn', 'siswas.nama', 'siswas.rombel_saat_ini',
                'siswas.status', 'siswas.tanggal_lahir', 'siswas.jk',
                'siswas.tahun_pelajaran_id'
            )
            ->whereNotNull('siswas.nisn');

        if ($statusFilter !== 'Semua') {
            $query->where('siswas.status', $statusFilter);
        }

        if ($angkatan) {
            $query->where('siswas.tahun_masuk', $angkatan);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('siswas.nama', 'like', "%{$search}%")
                  ->orWhere('siswas.nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('siswas.tahun_masuk', 'asc')
            ->orderBy('siswas.nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Map nisn => BukuInduk record (with kelengkapan accessor)
        $nisnList     = $siswas->pluck('nisn')->filter()->toArray();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisnList)->get()->keyBy('nisn');

        // Build kelengkapan that merges Siswa data into the calculation
        // Attach the matched Siswa row onto each BukuInduk so the accessor can use it
        $siswaByNisn  = $siswas->keyBy('nisn');
        foreach ($bukuIndukMap as $nisn => $bi) {
            $bi->setRelation('siswaPokok', $siswaByNisn[$nisn] ?? null);
        }

        return view('buku-induk.index', compact(
            'siswas', 'bukuIndukMap', 'search', 'statusFilter',
            'tahunPelajarans', 'tahunId', 'perPage', 'angkatans', 'angkatan'
        ));
    }

    /**
     * Show: Display the full Buku Induk for a student.
     */
    public function show(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        // Fetch the canonical siswa record (latest)
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        // Build academic grid: dynamic grades based on recorded data
        $availableGrades = $prestasis->pluck('kelas')->unique()->sort()->values()->toArray();
        // If no data, default to at least grade 1 if it's a new student, or use provided grade info
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

        return view('buku-induk.show', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans'));
    }

    /**
     * Edit: Show the edit form.
     */
    public function edit(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        return view('buku-induk.edit', compact('bukuInduk', 'siswa', 'mataPelajarans'));
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
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

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
        
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('buku-induk.print', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings'));
    }
}
