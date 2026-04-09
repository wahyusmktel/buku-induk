<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\User;
use App\Models\TahunPelajaran;
use App\Models\BukuInduk;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count();
        $totalAlumni     = Siswa::where('status', 'Lulus')->count();
        $totalRombel     = Rombel::count();
        
        // Data Tahun Pelajaran (terutama untuk Aksi Cepat SuperAdmin)
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $tahunAktif      = $tahunPelajarans->firstWhere('is_aktif', true);

        return view('dashboard', compact(
            'totalSiswaAktif',
            'totalAlumni',
            'totalRombel',
            'tahunPelajarans',
            'tahunAktif'
        ));
    }
}
