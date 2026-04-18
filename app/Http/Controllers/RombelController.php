<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\TahunPelajaran;
use App\Models\Siswa;
use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class RombelController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        $jenjangSetting = Setting::where('key', 'jenjang_pendidikan')->first();
        $jenjang = $jenjangSetting ? $jenjangSetting->value : 'SD';
        
        $canCopy = false;
        if ($tahunAktif) {
            $rombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)
                ->withCount(['siswas' => function($q) {
                    $q->where('status', 'Aktif');
                }])
                ->get();

            // Ambil nama-nama rombel yang sudah ada di semester aktif
            $currentRombelNames = $rombels->pluck('nama')->toArray();

            // Tombol salin rombel (metadata)
            $canCopy = Rombel::where('tahun_pelajaran_id', '!=', $tahunAktif->id)
                ->whereNotIn('nama', $currentRombelNames)
                ->exists();

            // Tombol salin anggota rombel
            // Muncul jika: tahun aktif sudah memiliki rombel (ada tujuan salin),
            // belum ada siswa yang masuk ke rombel mana pun, dan ada siswa di tahun sebelumnya.
            $hasRombelsInCurrentYear = $rombels->isNotEmpty();

            $hasMembersInCurrentYear = Siswa::where('tahun_pelajaran_id', $tahunAktif->id)
                ->whereNotNull('rombel_id')
                ->exists();
            
            $hasPreviousYearWithStudents = Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', '!=', $tahunAktif->id)
                ->exists();

            $canCopyMembers = $hasRombelsInCurrentYear && !$hasMembersInCurrentYear && $hasPreviousYearWithStudents;
        } else {
            $rombels = collect();
            $canCopyMembers = false;
        }

        return view('rombels.index', compact('rombels', 'tahunAktif', 'jenjang', 'canCopy', 'canCopyMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_rombel' => 'required|in:Kelas,Pilihan',
            'tingkat' => 'nullable|integer|min:1|max:12',
            'kompetensi_keahlian' => 'nullable|string|max:255',
            'kurikulum' => 'nullable|string|max:255',
            'guru_id' => 'nullable|string', // uuid, nullable since feature doesn't exist yet
        ]);

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada Tahun Pelajaran aktif. Silakan set tahun pelajaran aktif terlebih dahulu.');
        }

        // Check if rombel already exists this year
        if (Rombel::where('nama', $request->nama)->where('tahun_pelajaran_id', $tahunAktif->id)->exists()) {
            return redirect()->back()->with('error', 'Nama rombel/kelas sudah ada di tahun pelajaran ini.')->withInput();
        }

        Rombel::create([
            'nama' => $request->nama,
            'jenis_rombel' => $request->jenis_rombel,
            'tingkat' => $request->tingkat,
            'kompetensi_keahlian' => $request->kompetensi_keahlian,
            'kurikulum' => $request->kurikulum,
            'guru_id' => $request->guru_id,
            'tahun_pelajaran_id' => $tahunAktif->id,
        ]);

        return redirect()->route('rombels.index')->with('success', 'Rombongan Belajar berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_rombel' => 'required|in:Kelas,Pilihan',
            'tingkat' => 'nullable|integer|min:1|max:12',
            'kompetensi_keahlian' => 'nullable|string|max:255',
            'kurikulum' => 'nullable|string|max:255',
            'guru_id' => 'nullable|string',
        ]);

        $rombel = Rombel::findOrFail($id);
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();

        if ($tahunAktif) {
            // Check if another rombel with the same name already exists in this year
            if (Rombel::where('nama', $request->nama)
                ->where('tahun_pelajaran_id', $tahunAktif->id)
                ->where('id', '!=', $rombel->id)
                ->exists()) {
                return redirect()->back()->with('error', 'Nama rombel/kelas sudah ada di tahun pelajaran ini.')->withInput();
            }
        }

        $rombel->update([
            'nama' => $request->nama,
            'jenis_rombel' => $request->jenis_rombel,
            'tingkat' => $request->tingkat,
            'kompetensi_keahlian' => $request->kompetensi_keahlian,
            'kurikulum' => $request->kurikulum,
            'guru_id' => $request->guru_id,
        ]);

        return redirect()->route('rombels.index')->with('success', 'Data Rombongan Belajar berhasil diperbarui!');
    }

    public function show($id)
    {
        $rombel = Rombel::with(['siswas' => function($q) {
            $q->where('status', 'Aktif')->orderBy('nama', 'asc');
        }])->findOrFail($id);
        
        return view('rombels.show', compact('rombel'));
    }

    public function getUnassignedSiswas(Request $request, $id)
    {
        $rombel = Rombel::findOrFail($id);
        $search = $request->get('search');

        // Query siswas yang belum memiliki rombel_id dan status 'Aktif'
        $query = \App\Models\Siswa::whereNull('rombel_id')
            ->where('status', 'Aktif');

        // Jika rombel punya tingkat pendidkan, disesuaikan.
        if ($rombel->tingkat) {
            $query->where('tingkat_kelas', $rombel->tingkat);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nisn', 'LIKE', "%{$search}%")
                  ->orWhere('nipd', 'LIKE', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('nama', 'asc')->limit(50)->get(['id', 'nama', 'nisn', 'nipd', 'jk', 'tingkat_kelas']);

        return response()->json($siswas);
    }

    public function assignSiswas(Request $request, $id)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'siswa_ids.*' => 'exists:siswas,id',
        ]);

        $rombel = Rombel::findOrFail($id);
        
        \App\Models\Siswa::whereIn('id', $request->siswa_ids)
            ->update([
                'rombel_id' => $rombel->id,
                'rombel_saat_ini' => $rombel->nama,
            ]);

        return redirect()->back()->with('success', count($request->siswa_ids) . ' siswa berhasil dipetakan ke Rombel ' . $rombel->nama);
    }

    public function getPreview($tahunId)
    {
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        $existingRombelNames = [];

        if ($tahunAktif) {
            $existingRombelNames = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)
                ->pluck('nama')
                ->toArray();
        }

        $rombels = Rombel::where('tahun_pelajaran_id', $tahunId)
            ->whereNotIn('nama', $existingRombelNames)
            ->get();
            
        return response()->json($rombels);
    }

    public function copyFromSemester(Request $request)
    {
        $request->validate([
            'source_tahun_id' => 'required|exists:tahun_pelajarans,id',
            'selected_rombel_ids' => 'required|array',
            'selected_rombel_ids.*' => 'exists:rombels,id',
        ]);

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun pelajaran aktif.');
        }

        $sourceTahun = TahunPelajaran::findOrFail($request->source_tahun_id);
        $sourceRombels = Rombel::where('tahun_pelajaran_id', $sourceTahun->id)
            ->whereIn('id', $request->selected_rombel_ids)
            ->get();

        if ($sourceRombels->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada rombel yang dipilih atau ditemukan.');
        }

        $count = 0;
        DB::transaction(function () use ($sourceRombels, $tahunAktif, &$count) {
            foreach ($sourceRombels as $rombel) {
                // Prevent duplicates by checking name and target year
                $exists = Rombel::where('nama', $rombel->nama)
                    ->where('tahun_pelajaran_id', $tahunAktif->id)
                    ->exists();

                if (!$exists) {
                    Rombel::create([
                        'nama' => $rombel->nama,
                        'tingkat' => $rombel->tingkat,
                        'tahun_pelajaran_id' => $tahunAktif->id,
                        'jenis_rombel' => $rombel->jenis_rombel,
                        'kompetensi_keahlian' => $rombel->kompetensi_keahlian,
                        'kurikulum' => $rombel->kurikulum,
                        'guru_id' => null, // Do not copy home room teacher as it might change
                    ]);
                    $count++;
                }
            }
        });

        ActivityLogService::log('rombel_copy', "Menyalin {$count} rombel dari {$sourceTahun->tahun} - {$sourceTahun->semester} ke tahun aktif.", [
            'source_id' => $sourceTahun->id,
            'target_id' => $tahunAktif->id,
            'count' => $count
        ]);

        return redirect()->back()->with('success', "Berhasil menyalin {$count} rombel dari semester sebelumnya.");
    }

    public function getPreviewMembers($tahunId)
    {
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunId)
            ->withCount(['siswas' => function($q) {
                $q->withoutGlobalScope('tahun_aktif')->where('status', 'Aktif');
            }])
            ->get();
            
        return response()->json($rombels);
    }

    public function copyMembers(Request $request)
    {
        $request->validate([
            'source_tahun_id' => 'required|exists:tahun_pelajarans,id',
        ]);

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun pelajaran aktif.');
        }

        $sourceTahun = TahunPelajaran::findOrFail($request->source_tahun_id);
        
        // Ambil semua rombel di tahun aktif untuk pemetaan (berdasarkan nama)
        $targetRombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)->get()->keyBy('nama');
        
        // Ambil semua rombel di tahun sumber yang memiliki siswa aktif
        $sourceRombels = Rombel::where('tahun_pelajaran_id', $sourceTahun->id)
            ->with(['siswas' => function($q) {
                $q->withoutGlobalScope('tahun_aktif')->where('status', 'Aktif');
            }])
            ->get();

        $studentCount = 0;
        $rombelCount = 0;

        DB::transaction(function () use ($sourceRombels, $targetRombels, $tahunAktif, &$studentCount, &$rombelCount) {
            foreach ($sourceRombels as $sourceRombel) {
                $targetRombel = $targetRombels->get($sourceRombel->nama);
                
                if ($targetRombel && $sourceRombel->siswas->isNotEmpty()) {
                    $rombelCount++;
                    foreach ($sourceRombel->siswas as $oldSiswa) {
                        // Cari apakah siswa ini sudah ada di tahun aktif (misal dipromosikan lewat menu Siswa)
                        $newSiswa = Siswa::withoutGlobalScope('tahun_aktif')
                            ->where('tahun_pelajaran_id', $tahunAktif->id)
                            ->where(function($q) use ($oldSiswa) {
                                if ($oldSiswa->nisn) {
                                    $q->where('nisn', $oldSiswa->nisn);
                                } elseif ($oldSiswa->nik) {
                                    $q->where('nik', $oldSiswa->nik);
                                } else {
                                    $q->where('nama', $oldSiswa->nama)
                                      ->where('tanggal_lahir', $oldSiswa->tanggal_lahir);
                                }
                            })->first();

                        if ($newSiswa) {
                            // Update rombel_id ke rombel baru yang namanya sama
                            $newSiswa->update([
                                'rombel_id' => $targetRombel->id,
                                'rombel_saat_ini' => $targetRombel->nama
                            ]);
                        } else {
                            // Replicate siswa ke tahun aktif jika belum ada
                            $newSiswa = $oldSiswa->replicate();
                            $newSiswa->tahun_pelajaran_id = $tahunAktif->id;
                            $newSiswa->rombel_id = $targetRombel->id;
                            $newSiswa->rombel_saat_ini = $targetRombel->nama;
                            $newSiswa->save();
                        }
                        $studentCount++;
                    }
                }
            }
        });

        ActivityLogService::log('rombel_members_copy', "Menyalin {$studentCount} anggota siswa ke {$rombelCount} rombel dari {$sourceTahun->tahun}.", [
            'source_id' => $sourceTahun->id,
            'target_id' => $tahunAktif->id,
            'student_count' => $studentCount,
            'rombel_count' => $rombelCount
        ]);

        return redirect()->back()->with('success', "Berhasil menyalin {$studentCount} anggota siswa ke {$rombelCount} rombel.");
    }
}
