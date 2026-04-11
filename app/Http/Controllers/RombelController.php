<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

use App\Models\Setting;

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
}
