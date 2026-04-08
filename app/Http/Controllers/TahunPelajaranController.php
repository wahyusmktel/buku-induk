<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

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

        ActivityLogService::log('tahun_pelajaran_add', "Menambahkan Tahun Pelajaran baru: {$request->tahun} - {$request->semester}", [
            'tahun' => $request->tahun,
            'semester' => $request->semester
        ]);

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    public function copyData($id)
    {
        $targetYear = TahunPelajaran::findOrFail($id);
        
        // Restriction: Only allow Copy if Target is Genap and Source is Ganjil in SAME YEAR
        if ($targetYear->semester !== 'Genap') {
            return redirect()->back()->with('error', 'Penyalinan data hanya diizinkan untuk semester Genap.');
        }

        $sourceYear = TahunPelajaran::where('tahun', $targetYear->tahun)
            ->where('semester', 'Ganjil')
            ->where('id', '!=', $id)
            ->first();

        if (!$sourceYear) {
            return redirect()->back()->with('error', 'Gagal menyalin: Tidak ditemukan data semester Ganjil pada tahun ajaran yang sama.');
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

        ActivityLogService::log('tahun_pelajaran_copy', "Menyalin data dari sesi {$sourceYear->tahun} - {$sourceYear->semester} ke {$targetYear->tahun} - {$targetYear->semester}", [
            'source_id' => $sourceYear->id,
            'target_id' => $targetYear->id
        ]);

        return redirect()->back()->with('success', "Berhasil menyalin data dari sesi {$sourceYear->tahun} - {$sourceYear->semester}.");
    }

    public function activate($id)
    {
        $tp = TahunPelajaran::findOrFail($id);

        DB::transaction(function () use ($tp) {
            // Deactivate all
            TahunPelajaran::query()->update(['is_aktif' => false]);
            
            // Activate selected
            $tp->update(['is_aktif' => true]);

            // Backfill existing students with null academic year to this active year
            \App\Models\Siswa::withoutGlobalScope('tahun_aktif')
                ->whereNull('tahun_pelajaran_id')
                ->update(['tahun_pelajaran_id' => $tp->id]);
        });

        ActivityLogService::log('tahun_pelajaran_activate', "Mengaktifkan Tahun Pelajaran: {$tp->tahun} - {$tp->semester}", [
            'tahun_id' => $tp->id,
            'tahun' => $tp->tahun,
            'semester' => $tp->semester
        ]);

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil diaktifkan dan data siswa tanpa tahun telah dikaitkan.');
    }

    public function destroy($id)
    {
        $tp = TahunPelajaran::findOrFail($id);
        
        if ($tp->siswas()->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus tahun pelajaran yang sudah memiliki data siswa.');
        }

        $info = "{$tp->tahun} - {$tp->semester}";
        $tp->delete();

        ActivityLogService::log('tahun_pelajaran_delete', "Menghapus Tahun Pelajaran: {$info}", [
            'info' => $info
        ]);

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }
}
