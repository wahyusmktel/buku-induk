<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\PrestasiBelajar;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    /**
     * Store or update a prestasi record for a given buku induk.
     */
    public function store(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $validated = $request->validate([
            'kelas'                => 'required|integer|between:1,6',
            'semester'             => 'required|integer|between:1,2',
            'tahun_pelajaran'      => 'required|string|max:20',
            'nilai'                => 'nullable|array',
            'nilai.*'              => 'nullable|numeric|between:0,100',
            'peringkat'            => 'nullable|integer|min:1',
            'sikap'                => 'nullable|string|max:50',
            'kerajinan'            => 'nullable|string|max:50',
            'kebersihan_kerapian'  => 'nullable|string|max:50',
            'hadir_sakit'          => 'nullable|integer|min:0',
            'hadir_izin'           => 'nullable|integer|min:0',
            'hadir_alpha'          => 'nullable|integer|min:0',
            'keterangan_kenaikan'  => 'nullable|string|max:50',
            'tgl_keputusan_kenaikan' => 'nullable|date',
            'catatan_guru'         => 'nullable|string',
        ]);

        $validated['buku_induk_id'] = $bukuInduk->id;

        $prestasi = PrestasiBelajar::updateOrCreate(
            [
                'buku_induk_id' => $bukuInduk->id,
                'kelas'         => $validated['kelas'],
                'semester'      => $validated['semester'],
            ],
            \Illuminate\Support\Arr::except($validated, ['nilai'])
        );

        if (!empty($validated['nilai'])) {
            foreach ($validated['nilai'] as $mapelId => $score) {
                if (!empty($score) || $score === '0' || $score === 0) {
                    \App\Models\PrestasiNilai::updateOrCreate(
                        [
                            'prestasi_belajar_id' => $prestasi->id,
                            'mata_pelajaran_id' => $mapelId,
                        ],
                        ['nilai' => $score]
                    );
                } else {
                    \App\Models\PrestasiNilai::where('prestasi_belajar_id', $prestasi->id)
                        ->where('mata_pelajaran_id', $mapelId)
                        ->delete();
                }
            }
        }
        
        $prestasi->recalculateTotals();

        return redirect()->route('buku-induk.show', $nisn)
            ->with('success', "Data prestasi Kelas {$validated['kelas']} Semester {$validated['semester']} berhasil disimpan.");
    }

    /**
     * Destroy a prestasi record.
     */
    public function destroy(string $nisn, PrestasiBelajar $prestasi)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        if ($prestasi->buku_induk_id !== $bukuInduk->id) {
            abort(403);
        }

        $prestasi->delete();
        return redirect()->route('buku-induk.show', $nisn)->with('success', 'Data prestasi berhasil dihapus.');
    }
}
