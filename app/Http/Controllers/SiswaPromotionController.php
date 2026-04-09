<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class SiswaPromotionController extends Controller
{
    /**
     * Display the promotion interface.
     */
    public function index(Request $request)
    {
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        $sourceTahunId = $request->get('source_tahun_id', $tahunAktif?->id);
        $sourceRombelId = $request->get('source_rombel_id');
        
        $tahunPelajarans = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();
        
        $rombels = [];
        $siswas = [];
        
        if ($sourceTahunId) {
            $rombels = Rombel::where('tahun_pelajaran_id', $sourceTahunId)->get();
        }
        
        if ($sourceRombelId) {
            $siswas = Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', $sourceTahunId)
                ->where('rombel_id', $sourceRombelId)
                ->where('status', 'Aktif')
                ->orderBy('nama')
                ->get();
        }

        // Potential target sessions
        $targetSessions = TahunPelajaran::where('id', '!=', $sourceTahunId)->orderByDesc('tahun')->get();

        return view('siswas.promote', compact(
            'tahunPelajarans', 'sourceTahunId', 'sourceRombelId', 
            'rombels', 'siswas', 'targetSessions'
        ));
    }

    /**
     * Handle the promotion request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'target_tahun_id' => 'required|exists:tahun_pelajarans,id',
            'target_rombel_nama' => 'required|string', // Support creating new rombel name
            'promote_status' => 'required|in:Aktif,Lulus',
            'update_batch' => 'nullable|boolean',
        ]);

        $targetTahun = TahunPelajaran::findOrFail($request->target_tahun_id);
        
        // Find or Create Target Rombel in Target Year
        $targetRombel = Rombel::firstOrCreate([
            'nama' => $request->target_rombel_nama,
            'tahun_pelajaran_id' => $targetTahun->id,
        ]);

        $count = 0;
        DB::transaction(function () use ($request, $targetTahun, $targetRombel, &$count) {
            foreach ($request->siswa_ids as $siswaId) {
                $oldSiswa = Siswa::withoutGlobalScope('tahun_aktif')->findOrFail($siswaId);
                
                // Check if already promoted to this year (unique check by NISN)
                if ($oldSiswa->nisn) {
                    $exists = Siswa::withoutGlobalScope('tahun_aktif')
                        ->where('nisn', $oldSiswa->nisn)
                        ->where('tahun_pelajaran_id', $targetTahun->id)
                        ->exists();
                    
                    if ($exists) continue;
                }

                $newSiswa = $oldSiswa->replicate();
                $newSiswa->tahun_pelajaran_id = $targetTahun->id;
                $newSiswa->rombel_id = $targetRombel->id;
                $newSiswa->rombel_saat_ini = $targetRombel->nama;
                $newSiswa->status = $request->promote_status;
                
                // If requested, update tahun_masuk (Batch) to the current target year's starting session if empty
                if ($request->update_batch && empty($newSiswa->tahun_masuk)) {
                    $newSiswa->tahun_masuk = substr($targetTahun->tahun, 0, 4);
                }
                
                $newSiswa->save();
                $count++;
            }
        });

        ActivityLogService::log('siswa_promote', "Promosi/Naik Kelas Massal: Berhasil memindahkan {$count} siswa ke {$targetTahun->tahun} - {$targetRombel->nama}", [
            'count' => $count,
            'target_tahun' => $targetTahun->tahun,
            'target_rombel' => $targetRombel->nama
        ]);

        return redirect()->route('siswas.promote.index', [
            'source_tahun_id' => $request->source_tahun_id,
            'source_rombel_id' => $request->source_rombel_id
        ])->with('success', "Berhasil mempromosikan {$count} siswa ke {$targetTahun->tahun} ({$targetRombel->nama}).");
    }

    /**
     * Ajax to fetch rombels by year.
     */
    public function getRombelsByYear($tahunId)
    {
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunId)->get();
        return response()->json($rombels);
    }
}
