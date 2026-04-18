<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

// ROUTES NEEDED: GET /api/search → SearchController@search

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $siswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nama', 'like', "%{$q}%")
            ->orWhere('nisn', 'like', "%{$q}%")
            ->orWhere('nipd', 'like', "%{$q}%")
            ->with('tahunPelajaran')
            ->limit(8)
            ->get();

        $results = $siswas->map(function ($s) {
            return [
                'type'          => 'siswa',
                'id'            => $s->id,
                'nisn'          => $s->nisn,
                'nama'          => $s->nama,
                'kelas'         => $s->tingkat_kelas,
                'tahun'         => $s->tahunPelajaran?->tahun,
                'url_profil'    => route('siswas.show', $s->id),
                'url_buku_induk'=> $s->nisn ? route('buku-induk.show', $s->nisn) : null,
            ];
        });

        return response()->json($results);
    }
}
