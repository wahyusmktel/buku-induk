<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\TahunPelajaran;
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
        
        if (!$tahunAktif) {
            $rombels = collect();
        } else {
            // Kita hilangkan mandatory whereHas('siswas') agar rombel kosong tetap tampil dan bisa ditambahkan siswa nantinya
            // Atau tetap dipertahankan? Karena sebelumnya difilter whereHas siswa...
            // Karena sekarang kita bisa tambah rombel kosong, sebaiknya whereHas dihapus agar rombel baru muncul di list.
            $rombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)
                ->withCount(['siswas' => function($q) {
                    $q->where('status', 'Aktif');
                }])
                ->get();
        }

        return view('rombels.index', compact('rombels', 'tahunAktif', 'jenjang'));
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
            ->update(['rombel_id' => $rombel->id]);

        return redirect()->back()->with('success', count($request->siswa_ids) . ' siswa berhasil dipetakan ke Rombel ' . $rombel->nama);
    }

    public function getPreview($tahunId)
    {
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunId)->get();
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
}
