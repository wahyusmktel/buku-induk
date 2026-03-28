<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        
        if (!$tahunAktif) {
            $rombels = collect();
        } else {
            $rombels = Rombel::where('tahun_pelajaran_id', $tahunAktif->id)
                ->withCount('siswas')
                ->get();
        }

        return view('rombels.index', compact('rombels', 'tahunAktif'));
    }

    public function show($id)
    {
        $rombel = Rombel::with(['siswas' => function($q) {
            $q->orderBy('nama', 'asc');
        }])->findOrFail($id);
        
        return view('rombels.show', compact('rombel'));
    }
}
