<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use App\Imports\SiswaImport;
use App\Imports\MasterBukuIndukImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;


class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'Aktif');
        $tingkat = $request->get('tingkat');
        $rombel = $request->get('rombel');
        $search = $request->get('search');
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        
        $query = Siswa::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nipd', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        if ($tingkat) {
            $query->where('tingkat_kelas', $tingkat);
        }

        if ($rombel) {
            $query->where(function($q) use ($rombel) {
                $q->where('rombel_saat_ini', $rombel)
                  ->orWhereHas('rombel', function($rq) use ($rombel) {
                      $rq->where('nama', $rombel);
                  });
            });
        }

        $siswas = $query->latest()->paginate(15)->withQueryString();
        
        // Cek apakah bisa melakukan Naikan Semester atau Naikan Kelas
        $canPromote = false;
        $canPromoteGrade = false;
        $previousGenapId = null;
        $semesterSumber = null;

        if ($tahunAktif) {
            $isCurrentEmpty = $siswas->total() === 0;

            if ($isCurrentEmpty) {
                // --- Deteksi Naikan Semester: transisi antar semester dalam tahun yang SAMA ---
                $otherSemester = $tahunAktif->semester === 'Ganjil' ? 'Genap' : 'Ganjil';
                $sameYearOther = TahunPelajaran::where('tahun', $tahunAktif->tahun)
                    ->where('semester', $otherSemester)
                    ->where('id', '!=', $tahunAktif->id)
                    ->first();

                if ($sameYearOther && Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $sameYearOther->id)
                    ->where('status', 'Aktif')
                    ->exists()) {
                    $canPromote = true;
                    $previousGenapId = $sameYearOther->id;
                    $semesterSumber = $sameYearOther;
                }

                // --- Deteksi Naikan Kelas: transisi ke tahun pelajaran BARU (tahun berbeda) ---
                if (!$canPromote) {
                    $prevWithStudents = TahunPelajaran::where('tahun', '!=', $tahunAktif->tahun)
                        ->orderByDesc('tahun')
                        ->orderByDesc('semester')
                        ->get()
                        ->first(fn($tp) => Siswa::withoutGlobalScope('tahun_aktif')
                            ->where('tahun_pelajaran_id', $tp->id)
                            ->where('status', 'Aktif')
                            ->exists());

                    if ($prevWithStudents) {
                        $canPromoteGrade = true;
                        $previousGenapId = $prevWithStudents->id;
                    }
                }
            }
        }

        // Ambil daftar rombel dari tabel Rombel untuk tahun aktif agar lebih akurat
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunAktif?->id)
            ->orderBy('nama')
            ->pluck('nama');

        return view('siswas.index', compact('siswas', 'tahunAktif', 'status', 'tingkat', 'rombel', 'rombels', 'canPromote', 'canPromoteGrade', 'previousGenapId', 'semesterSumber'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        Log::info('Import request received', [
            'has_file' => $request->hasFile('file'),
            'file_is_valid' => $file ? $file->isValid() : 'no file',
            'file_error' => $file ? $file->getError() : 'no file',
            'user' => auth()->user()->id
        ]);



        // Check Role
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            Log::warning('Unauthorized import attempt', ['user' => auth()->user()->email]);
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk melakukan import data.');
        }

        $request->validate([
            'file' => 'required'
        ]);

        // Check Active Tahun Pelajaran
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Gagal melakukan import: Tidak ada Tahun Pelajaran yang aktif. Silakan aktifkan tahun pelajaran terlebih dahulu di menu Tahun Pelajaran.');
        }

        try {
            $filename = time() . '_' . $file->getClientOriginalName();
            $tempDir = storage_path('app/private/temp');
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file->move($tempDir, $filename);
            $path = 'temp/' . $filename;
            
            $import = new SiswaImport;
            Excel::import($import, $path);
            
            Storage::delete($path);

            // --- AUTOMATIC GRADUATION / ARCHIVING LOGIC ---
            
            // 1. Current Session Students (just processed)
            $currentSessionIds = $import->processedSiswaIds;
            $currentSessionNisnNik = Siswa::whereIn('id', $currentSessionIds)
                ->get(['nisn', 'nik'])
                ->map(fn($s) => [$s->nisn, $s->nik])
                ->flatten()
                ->filter()
                ->toArray();

            // 2. Find Previous Session
            $previousSession = \App\Models\TahunPelajaran::where('id', '!=', $tahunAktif->id)
                ->where(function($q) use ($tahunAktif) {
                    $q->where('tahun', '<', $tahunAktif->tahun)
                      ->orWhere(function($sub) use ($tahunAktif) {
                          $sub->where('tahun', $tahunAktif->tahun)
                              ->where('semester', 'Ganjil'); // If current is Genap, previous is Ganjil
                      });
                })
                ->orderBy('tahun', 'desc')
                ->orderBy('semester', 'desc') // Genap (E) < Ganjil (A) in sorting? Wait, Ga-njil vs Ge-nap. Ga < Ge. So desc for Genap.
                ->first();

            $graduatedPrevCount = 0;
            $departedPrevCount = 0;

            if ($previousSession) {
                // Find students who were Aktif in the previous session
                $prevStudents = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $previousSession->id)
                    ->where('status', 'Aktif')
                    ->get();

                foreach ($prevStudents as $oldSiswa) {
                    // Check if this student exists in the CURRENT session
                    $existsInCurrent = false;
                    if ($oldSiswa->nisn && in_array($oldSiswa->nisn, $currentSessionNisnNik)) {
                        $existsInCurrent = true;
                    } elseif ($oldSiswa->nik && in_array($oldSiswa->nik, $currentSessionNisnNik)) {
                        $existsInCurrent = true;
                    }

                    if (!$existsInCurrent) {
                        // They are gone in the new session. Mark them in the PREVIOUS session.
                        if ($this->isJenjangAkhir($oldSiswa->rombel_saat_ini)) {
                            // 1. Mark as Lulus in the PREVIOUS session for archive
                            $oldSiswa->update(['status' => 'Lulus']);
                            $graduatedPrevCount++;

                            // 2. Also register them as Lulus in the CURRENT session (Tahun Terbaru)
                            // check if they already exist in current session (unlikely if missing from Excel)
                            $currentDuplicate = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)
                                ->where(function($q) use ($oldSiswa) {
                                    if ($oldSiswa->nisn) $q->where('nisn', $oldSiswa->nisn);
                                    if ($oldSiswa->nik) $q->orWhere('nik', $oldSiswa->nik);
                                })->first();

                            if (!$currentDuplicate) {
                                $newLulusRecord = $oldSiswa->replicate();
                                $newLulusRecord->tahun_pelajaran_id = $tahunAktif->id;
                                $newLulusRecord->status = 'Lulus';
                                $newLulusRecord->save();
                            } else {
                                $currentDuplicate->update(['status' => 'Lulus']);
                            }
                        } else {
                            $oldSiswa->update(['status' => 'Keluar/Mutasi']);
                            $departedPrevCount++;
                        }
                    }
                }
            }

            // 3. Current Session "Missing" (for cases like manual import/copy data)
            $unprocessedInCurrent = Siswa::whereNotIn('id', $currentSessionIds)->get();
            $graduatedCurrCount = 0;
            $departedCurrCount = 0;

            foreach ($unprocessedInCurrent as $siswa) {
                if ($this->isJenjangAkhir($siswa->rombel_saat_ini)) {
                    $siswa->update(['status' => 'Lulus']);
                    $graduatedCurrCount++;
                } else {
                    $siswa->update(['status' => 'Keluar/Mutasi']);
                    $departedCurrCount++;
                }
            }

            $message = "Import Dapodik berhasil diselesaikan. ";
            $message .= "Resume: {$import->createdCount} Siswa Baru, ";
            $message .= "{$import->updatedCount} Siswa diperbarui. ";
            
            if ($graduatedPrevCount > 0 || $departedPrevCount > 0) {
                $message .= "Arsip Sesi Lalu ({$previousSession->tahun}): {$graduatedPrevCount} Lulus, {$departedPrevCount} Keluar.";
            }
            
            if ($graduatedCurrCount > 0 || $departedCurrCount > 0) {
                $message .= " Arsip Sesi Ini: {$graduatedCurrCount} Lulus, {$departedCurrCount} Keluar.";
            }
            
            ActivityLogService::log('dapodik_import', "Import data Dapodik berhasil: {$import->createdCount} baru, {$import->updatedCount} diperbarui.", [
                'created' => $import->createdCount,
                'updated' => $import->updatedCount,
                'graduated' => $graduatedPrevCount + $graduatedCurrCount,
                'departed' => $departedPrevCount + $departedCurrCount
            ]);

            return redirect()->route('siswas.index')->with('success', $message);
        } catch (\Exception $e) {
            // Clean up if something went wrong but we have a path
            if (isset($path)) {
                Storage::delete($path);
            }
            
            Log::error('Import failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user' => auth()->user()->id
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan import: ' . $e->getMessage());
        }

    }

    public function show(Siswa $siswa)
    {
        return view('siswas.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }
        
        return view('siswas.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama'                    => 'required|string|max:255',
            'nama_panggilan'          => 'nullable|string|max:100',
            'nipd'                    => 'nullable|string|max:50',
            'jk'                      => 'required|in:L,P',
            'tingkat_kelas'           => 'nullable|integer|min:1|max:13',
            'tahun_masuk'             => 'nullable|integer',
            'nisn'                    => 'nullable|string|max:10',
            'nik'                     => 'nullable|string|max:16',
            'tempat_lahir'            => 'nullable|string|max:100',
            'tanggal_lahir'           => 'nullable|date',
            'agama'                   => 'nullable|string|max:50',
            'kewarganegaraan'         => 'nullable|string|max:50',
            'bahasa_sehari_hari'      => 'nullable|string|max:100',
            'golongan_darah'          => 'nullable|string|max:5',
            'riwayat_penyakit'        => 'nullable|string',
            'alamat'                  => 'nullable|string',
            'rt'                      => 'nullable|string|max:5',
            'rw'                      => 'nullable|string|max:5',
            'dusun'                   => 'nullable|string|max:100',
            'kelurahan'               => 'nullable|string|max:100',
            'kecamatan'               => 'nullable|string|max:100',
            'kode_pos'                => 'nullable|string|max:10',
            'lintang'                 => 'nullable|string|max:50',
            'bujur'                   => 'nullable|string|max:50',
            'jenis_tinggal'           => 'nullable|string|max:100',
            'alat_transportasi'       => 'nullable|string|max:100',
            'telepon'                 => 'nullable|string|max:20',
            'hp'                      => 'nullable|string|max:20',
            'email'                   => 'nullable|email|max:100',
            'skhun'                   => 'nullable|string|max:100',
            'penerima_kps'            => 'nullable|string|max:10',
            'no_kps'                  => 'nullable|string|max:100',
            'nomor_kks'               => 'nullable|string|max:100',
            'no_registrasi_akta_lahir' => 'nullable|string|max:100',
            'no_kk'                    => 'nullable|string|max:16',
            'anak_ke_berapa'           => 'nullable|integer',
            'jml_saudara_kandung'      => 'nullable|integer',
            'berat_badan'              => 'nullable|numeric',
            'tinggi_badan'             => 'nullable|numeric',
            'lingkar_kepala'           => 'nullable|numeric',
            'jarak_rumah_ke_sekolah_km'=> 'nullable|numeric',
            'nama_ayah'                => 'nullable|string|max:200',
            'nik_ayah'                 => 'nullable|string|max:16',
            'tahun_lahir_ayah'         => 'nullable|integer',
            'jenjang_pendidikan_ayah'  => 'nullable|string|max:100',
            'pekerjaan_ayah'           => 'nullable|string|max:100',
            'penghasilan_ayah'         => 'nullable|string|max:100',
            'nama_ibu'                 => 'nullable|string|max:200',
            'nik_ibu'                  => 'nullable|string|max:16',
            'tahun_lahir_ibu'          => 'nullable|integer',
            'jenjang_pendidikan_ibu'   => 'nullable|string|max:100',
            'pekerjaan_ibu'            => 'nullable|string|max:100',
            'penghasilan_ibu'          => 'nullable|string|max:100',
            'nama_wali'                 => 'nullable|string|max:200',
            'nik_wali'                  => 'nullable|string|max:16',
            'tahun_lahir_wali'          => 'nullable|integer',
            'jenjang_pendidikan_wali'   => 'nullable|string|max:100',
            'pekerjaan_wali'            => 'nullable|string|max:100',
            'penghasilan_wali'          => 'nullable|string|max:100',
            'rombel_saat_ini'           => 'nullable|string|max:255',
            'status'                    => 'required|in:Aktif,Lulus,Keluar/Mutasi',
            'no_peserta_un'             => 'nullable|string|max:100',
            'no_seri_ijazah'            => 'nullable|string|max:100',
            'penerima_kip'              => 'nullable|string|max:10',
            'nomor_kip'                 => 'nullable|string|max:100',
            'nama_di_kip'               => 'nullable|string|max:200',
            'layak_pip'                 => 'nullable|string|max:10',
            'alasan_layak_pip'          => 'nullable|string',
            'bank'                      => 'nullable|string|max:100',
            'nomor_rekening_bank'       => 'nullable|string|max:50',
            'rekening_atas_nama'        => 'nullable|string|max:200',
            'kebutuhan_khusus'          => 'nullable|string',
            'sekolah_asal'              => 'nullable|string|max:200',
        ]);

        $siswa->update($validated);

        ActivityLogService::log('siswa_update', "Memperbarui profil siswa: {$siswa->nama}", [
            'siswa_id' => $siswa->id,
            'nama' => $siswa->nama
        ]);

        return redirect()->route('siswas.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $namaSiswa = $siswa->nama;
        $siswa->delete();

        ActivityLogService::log('siswa_delete', "Menghapus data siswa: {$namaSiswa}", [
            'nama' => $namaSiswa
        ]);

        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil dihapus.');
    }
    /**
     * Helper to detect if a Rombel is a final grade (Lulus)
     */
    private function isJenjangAkhir($rombelNama)
    {
        if (!$rombelNama) return false;
        
        $name = strtolower($rombelNama);
        
        // 1. Cek status eksplisit
        $explicitPatterns = ['lulus', 'alumni', 'tamat'];
        foreach ($explicitPatterns as $p) {
            if (str_contains($name, $p)) return true;
        }

        /**
         * 2. Logika Deteksi Angka Jenjang (6, 9, 12)
         * Regex ini mencari angka 6, 9, atau 12 yang:
         * - Berada di awal string (^6...)
         * - Diawali spasi atau titik (.6... / 6...)
         * - Diikuti huruf, spasi, titik, atau akhir string (6c, 6.a, 6-b, 6 )
         * - Tapi bukan bagian dari angka lain (misal 16)
         */
        $finalGrades = ['6', '9', '12', 'vi', 'ix', 'xii'];
        foreach ($finalGrades as $grade) {
            // Regex: \b (word boundary) or start of string
            // Diikuti oleh $grade
            // Diikuti oleh karakter non-angka atau akhir string
            if (preg_match('/(^|[\s\.])' . preg_quote($grade, '/') . '([^0-9]|$)/i', $name)) {
                return true;
            }
        }

        // 3. Cek frase 'kelas x' (untuk romawi atau angka)
        $phrases = ['kelas 6', 'kelas vi', 'kelas 9', 'kelas ix', 'kelas 12', 'kelas xii'];
        foreach ($phrases as $phrase) {
            if (str_contains($name, $phrase)) return true;
        }

        return false;
    }

    public function masterImport(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        $file = $request->file('file');

        try {
            $filename = 'master_' . time() . '_' . $file->getClientOriginalName();
            $tempDir = storage_path('app/private/temp');
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file->move($tempDir, $filename);
            $path = 'temp/' . $filename;

            $import = new MasterBukuIndukImport();
            Excel::import($import, $path);

            // Clean up
            if (Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }

            ActivityLogService::log('master_import', "Master Import Buku Induk berhasil: {$import->createdCount} baru, {$import->updatedCount} diperbarui.", [
                'created' => $import->createdCount,
                'updated' => $import->updatedCount
            ]);

            return redirect()->route('siswas.index')->with('success', "Master Import berhasil: {$import->createdCount} data baru ditambahkan, {$import->updatedCount} data diperbarui.");
        } catch (\Exception $e) {
            // Clean up on failure
            if (isset($path) && Storage::disk('local')->exists($path)) {
                Storage::disk('local')->delete($path);
            }
            Log::error('Master Import Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses file Master Import: ' . $e->getMessage());
        }
    }

    public function getPreviewByYear($tahunId)
    {
        $siswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->with('rombel:id,nama')
            ->where('tahun_pelajaran_id', $tahunId)
            ->where('status', 'Aktif')
            ->orderBy('nama')
            ->get();
            
        $data = $siswas->map(function($s) {
            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'rombel_saat_ini' => $s->rombel ? $s->rombel->nama : ($s->rombel_saat_ini ?: '-')
            ];
        });
            
        return response()->json($data);
    }

    public function promoteSemester(Request $request)
    {
        $request->validate([
            'source_tahun_id' => 'required|exists:tahun_pelajarans,id',
        ]);

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun pelajaran aktif.');
        }

        $sourceTahun = TahunPelajaran::findOrFail($request->source_tahun_id);
        $sourceSiswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('tahun_pelajaran_id', $sourceTahun->id)
            ->where('status', 'Aktif')
            ->get();

        if ($sourceSiswas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ditemukan siswa aktif pada semester sumber.');
        }

        $count = 0;
        DB::transaction(function () use ($sourceSiswas, $tahunAktif, &$count) {
            // Pre-fetch rombels in target year for matching
            $targetRombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)->get()->keyBy('nama');

            foreach ($sourceSiswas as $oldSiswa) {
                // Prevent duplicates by checking NISN/NIK and target year
                $existsQuery = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $tahunAktif->id);
                
                if ($oldSiswa->nisn) {
                    $existsQuery->where('nisn', $oldSiswa->nisn);
                } elseif ($oldSiswa->nik) {
                    $existsQuery->where('nik', $oldSiswa->nik);
                } else {
                    // If no unique ID, match by name and birth date
                    $existsQuery->where('nama', $oldSiswa->nama)
                        ->where('tanggal_lahir', $oldSiswa->tanggal_lahir);
                }

                if ($existsQuery->exists()) {
                    continue;
                }

                $newSiswa = $oldSiswa->replicate();
                $newSiswa->tahun_pelajaran_id = $tahunAktif->id;
                
                // Match Rombel by Name if possible
                $matchRombel = $targetRombels->get($oldSiswa->rombel_saat_ini);
                if ($matchRombel) {
                    $newSiswa->rombel_id = $matchRombel->id;
                    $newSiswa->rombel_saat_ini = $matchRombel->nama;
                } else {
                    $newSiswa->rombel_id = null;
                    $newSiswa->rombel_saat_ini = null;
                }

                $newSiswa->save();
                $count++;
            }
        });

        ActivityLogService::log('siswa_promote_semester', "Menyalin {$count} siswa dari {$sourceTahun->tahun} - {$sourceTahun->semester} ke tahun aktif.", [
            'source_id' => $sourceTahun->id,
            'target_id' => $tahunAktif->id,
            'count' => $count
        ]);

        return redirect()->back()->with('success', "Berhasil menyalin {$count} data siswa dari semester sebelumnya.");
    }

    /**
     * API: Preview data siswa untuk naik kelas.
     * Menampilkan tingkat saat ini dan tingkat baru.
     */
    public function getGradePromotePreview($tahunId)
    {
        $jenjang = \App\Models\Setting::getValue('jenjang_pendidikan', 'SD');
        $tingkatAkhir = match($jenjang) {
            'SMP' => 3,
            'SMA/SMK' => 3,
            default => 6, // SD
        };

        $siswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->with('rombel:id,nama')
            ->where('tahun_pelajaran_id', $tahunId)
            ->where('status', 'Aktif')
            ->orderBy('tingkat_kelas')
            ->orderBy('nama')
            ->get();

        $data = $siswas->map(function($s) use ($tingkatAkhir) {
            $tingkatBaru = ($s->tingkat_kelas && $s->tingkat_kelas >= $tingkatAkhir) ? null : ($s->tingkat_kelas ? $s->tingkat_kelas + 1 : null);
            $willGraduate = $s->tingkat_kelas && $s->tingkat_kelas >= $tingkatAkhir;
            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'tingkat_kelas' => $s->tingkat_kelas,
                'tingkat_baru' => $tingkatBaru,
                'will_graduate' => $willGraduate,
                'rombel_saat_ini' => $s->rombel ? $s->rombel->nama : ($s->rombel_saat_ini ?: '-'),
            ];
        });

        $summary = [
            'total' => $data->count(),
            'naik' => $data->where('will_graduate', false)->count(),
            'lulus' => $data->where('will_graduate', true)->count(),
        ];

        return response()->json([
            'siswas' => $data->values(),
            'summary' => $summary,
        ]);
    }

    /**
     * POST: Proses naik kelas.
     * Menyalin data siswa dari semester Genap tahun sebelumnya ke semester Ganjil tahun baru.
     * Siswa naik satu tingkat, siswa tingkat akhir menjadi Lulus.
     */
    public function promoteGrade(Request $request)
    {
        $request->validate([
            'source_tahun_id' => 'required|exists:tahun_pelajarans,id',
        ]);

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun pelajaran aktif.');
        }

        $sourceTahun = TahunPelajaran::findOrFail($request->source_tahun_id);
        $sourceSiswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('tahun_pelajaran_id', $sourceTahun->id)
            ->where('status', 'Aktif')
            ->get();

        if ($sourceSiswas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ditemukan siswa aktif pada semester sumber.');
        }

        $jenjang = \App\Models\Setting::getValue('jenjang_pendidikan', 'SD');
        $tingkatAkhir = match($jenjang) {
            'SMP' => 3,
            'SMA/SMK' => 3,
            default => 6, // SD
        };

        $countNaik = 0;
        $countLulus = 0;

        DB::transaction(function () use ($sourceSiswas, $tahunAktif, $sourceTahun, $tingkatAkhir, &$countNaik, &$countLulus) {
            $targetRombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)->get()->keyBy('nama');

            foreach ($sourceSiswas as $oldSiswa) {
                // Prevent duplicates
                $existsQuery = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $tahunAktif->id);

                if ($oldSiswa->nisn) {
                    $existsQuery->where('nisn', $oldSiswa->nisn);
                } elseif ($oldSiswa->nik) {
                    $existsQuery->where('nik', $oldSiswa->nik);
                } else {
                    $existsQuery->where('nama', $oldSiswa->nama)
                        ->where('tanggal_lahir', $oldSiswa->tanggal_lahir);
                }

                if ($existsQuery->exists()) {
                    continue;
                }

                $isGraduating = $oldSiswa->tingkat_kelas && $oldSiswa->tingkat_kelas >= $tingkatAkhir;

                if ($isGraduating) {
                    // Siswa lulus — tandai di tahun sumber, TIDAK replicate ke tahun baru
                    $oldSiswa->update(['status' => 'Lulus']);
                    $countLulus++;
                } else {
                    // Siswa naik kelas — replicate ke tahun baru dengan tingkat + 1
                    $newSiswa = $oldSiswa->replicate();
                    $newSiswa->tahun_pelajaran_id = $tahunAktif->id;
                    $newSiswa->tingkat_kelas = $oldSiswa->tingkat_kelas ? $oldSiswa->tingkat_kelas + 1 : null;

                    // Match Rombel by name if possible
                    $matchRombel = $targetRombels->get($oldSiswa->rombel_saat_ini);
                    if ($matchRombel) {
                        $newSiswa->rombel_id = $matchRombel->id;
                        $newSiswa->rombel_saat_ini = $matchRombel->nama;
                    } else {
                        $newSiswa->rombel_id = null;
                        $newSiswa->rombel_saat_ini = null;
                    }

                    $newSiswa->save();
                    $countNaik++;
                }
            }
        });

        ActivityLogService::log('siswa_promote_grade', "Naik kelas: {$countNaik} siswa naik kelas, {$countLulus} siswa lulus dari {$sourceTahun->tahun} - {$sourceTahun->semester}.", [
            'source_id' => $sourceTahun->id,
            'target_id' => $tahunAktif->id,
            'count_naik' => $countNaik,
            'count_lulus' => $countLulus,
        ]);

        return redirect()->back()->with('success', "Naik Kelas berhasil: {$countNaik} siswa naik kelas, {$countLulus} siswa dinyatakan lulus.");
    }
}
