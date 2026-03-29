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
            'nilai_agama'          => 'nullable|numeric|between:0,100',
            'nilai_pkn'            => 'nullable|numeric|between:0,100',
            'nilai_bindo'          => 'nullable|numeric|between:0,100',
            'nilai_mtk'            => 'nullable|numeric|between:0,100',
            'nilai_ipa'            => 'nullable|numeric|between:0,100',
            'nilai_ips'            => 'nullable|numeric|between:0,100',
            'nilai_sbk'            => 'nullable|numeric|between:0,100',
            'nilai_pjok'           => 'nullable|numeric|between:0,100',
            'nilai_mulok'          => 'nullable|numeric|between:0,100',
            'nilai_mulok2'         => 'nullable|numeric|between:0,100',
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

        PrestasiBelajar::updateOrCreate(
            [
                'buku_induk_id' => $bukuInduk->id,
                'kelas'         => $validated['kelas'],
                'semester'      => $validated['semester'],
            ],
            $validated
        );

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
