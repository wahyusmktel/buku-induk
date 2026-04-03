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
        
        $bukuIndukList   = BukuInduk::get();
        // Load relation for accessory
        $bukuIndukList->each(function($bi) {
            $siswa = Siswa::withoutGlobalScope('tahun_aktif')->where('nisn', $bi->nisn)->orderBy('created_at', 'desc')->first();
            if ($siswa) {
                $bi->setRelation('siswaPokok', $siswa);
            }
        });
        $avgKelengkapan  = $bukuIndukList->count() > 0 ? round($bukuIndukList->avg('kelengkapan')) : 0;
        
        // Data Siswa Pindahan Baru (terbaru dibuat)
        $siswaBaru       = Siswa::orderBy('created_at', 'desc')->take(5)->get();
        
        // Riwayat Aktivitas (pseudo - berdasarkan update data siswa terakhir)
        $aktivitas       = Siswa::orderBy('updated_at', 'desc')->take(3)->get();
        
        // Data Tahun Pelajaran (terutama untuk Aksi Cepat SuperAdmin)
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $tahunAktif      = $tahunPelajarans->firstWhere('is_aktif', true);

        return view('dashboard', compact(
            'totalSiswaAktif',
            'totalAlumni',
            'totalRombel',
            'avgKelengkapan',
            'siswaBaru',
            'aktivitas',
            'tahunPelajarans',
            'tahunAktif'
        ));
    }
}
