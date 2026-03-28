<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunPelajaranController extends Controller
{
    public function index()
    {
        $tahunPelajarans = TahunPelajaran::withCount('siswas')->latest()->get();
        return view('tahun-pelajaran.index', compact('tahunPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:20',
            'semester' => 'required|string|in:Ganjil,Genap',
        ]);

        TahunPelajaran::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_aktif' => false,
        ]);

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    public function copyData($id)
    {
        $targetYear = TahunPelajaran::findOrFail($id);
        
        // Find previous year logic:
        // 2025/2026 Genap -> Source is 2025/2026 Ganjil
        // 2026/2027 Ganjil -> Source is 2025/2026 Genap
        $sourceYear = TahunPelajaran::where('id', '!=', $id)
            ->orderBy('tahun', 'desc')
            ->orderBy('semester', 'asc') // Ganjil is before Genap alphabetically
            ->where(function($q) use ($targetYear) {
                $q->where('tahun', '<', $targetYear->tahun)
                  ->orWhere(function($sub) use ($targetYear) {
                      $sub->where('tahun', $targetYear->tahun)
                          ->where('semester', 'Ganjil');
                  });
            })
            ->first();

        if (!$sourceYear) {
            return redirect()->back()->with('error', 'Gagal menyalin: Tidak ditemukan data Tahun Pelajaran sebelumnya.');
        }

        DB::transaction(function () use ($sourceYear, $targetYear) {
            // 1. Copy Rombels
            $rombels = \App\Models\Rombel::where('tahun_pelajaran_id', $sourceYear->id)->get();
            $rombelMapping = [];

            foreach ($rombels as $oldRombel) {
                $newRombel = \App\Models\Rombel::firstOrCreate([
                    'nama' => $oldRombel->nama,
                    'tahun_pelajaran_id' => $targetYear->id
                ]);
                $rombelMapping[$oldRombel->id] = $newRombel->id;
            }

            // 2. Copy Students (Cloning records)
            $students = \App\Models\Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', $sourceYear->id)
                ->get();

            foreach ($students as $oldSiswa) {
                $newSiswa = $oldSiswa->replicate();
                $newSiswa->tahun_pelajaran_id = $targetYear->id;
                $newSiswa->rombel_id = isset($oldSiswa->rombel_id) ? ($rombelMapping[$oldSiswa->rombel_id] ?? null) : null;
                $newSiswa->save();
            }
        });

        return redirect()->back()->with('success', "Berhasil menyalin data dari sesi {$sourceYear->tahun} - {$sourceYear->semester}.");
    }

    public function activate($id)
    {
        DB::transaction(function () use ($id) {
            // Deactivate all
            TahunPelajaran::query()->update(['is_aktif' => false]);
            
            // Activate selected
            $tp = TahunPelajaran::findOrFail($id);
            $tp->update(['is_aktif' => true]);

            // Backfill existing students with null academic year to this active year
            \App\Models\Siswa::withoutGlobalScope('tahun_aktif')
                ->whereNull('tahun_pelajaran_id')
                ->update(['tahun_pelajaran_id' => $tp->id]);
        });

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil diaktifkan dan data siswa tanpa tahun telah dikaitkan.');
    }

    public function destroy($id)
    {
        $tp = TahunPelajaran::findOrFail($id);
        
        if ($tp->siswas()->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus tahun pelajaran yang sudah memiliki data siswa.');
        }

        $tp->delete();
        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }
}
