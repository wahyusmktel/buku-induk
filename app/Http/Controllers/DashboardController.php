<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\User;
use App\Models\TahunPelajaran;
use App\Models\BukuInduk;

class DashboardController extends Controller
{
    public function index()
    {
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        $tahunAktif      = $tahunPelajarans->firstWhere('is_aktif', true);

        $totalSiswaAktif = Siswa::where('status', 'Aktif')->count(); // Tetap scoped ke tahun aktif
        $totalAlumni     = Siswa::withoutGlobalScope('tahun_aktif')->where('status', 'Lulus')->count();
        $totalRombel     = Rombel::where('tahun_pelajaran_id', $tahunAktif?->id)->count();

        // Distribusi siswa aktif per tingkat kelas
        $siswaPerTingkat = Siswa::where('status', 'Aktif')
            ->select(
                'tingkat_kelas',
                DB::raw('count(*) as total'),
                DB::raw("sum(case when jk='L' then 1 else 0 end) as laki"),
                DB::raw("sum(case when jk='P' then 1 else 0 end) as perempuan")
            )
            ->groupBy('tingkat_kelas')
            ->orderBy('tingkat_kelas')
            ->get();

        // Rombel pada tahun aktif yang tidak memiliki anggota
        $rombelTanpaAnggota = Rombel::whereDoesntHave('siswas')
            ->where('tahun_pelajaran_id', $tahunAktif?->id)
            ->count();

        // Buku induk yang belum memiliki foto (data tidak lengkap)
        $bukuIndukKurang = BukuInduk::whereNull('foto_1')->count();

        // Tren jumlah siswa per tahun pelajaran (untuk chart)
        $trendPerTahun = TahunPelajaran::withCount([
            'siswas as total_siswa'  => fn($q) => $q->withoutGlobalScope('tahun_aktif'),
            'siswas as siswa_aktif'  => fn($q) => $q->withoutGlobalScope('tahun_aktif')->where('status', 'Aktif'),
            'siswas as siswa_lulus'  => fn($q) => $q->withoutGlobalScope('tahun_aktif')->where('status', 'Lulus'),
        ])
        ->orderBy('tahun')
        ->orderBy('semester')
        ->get();

        return view('dashboard', compact(
            'totalSiswaAktif',
            'totalAlumni',
            'totalRombel',
            'tahunPelajarans',
            'tahunAktif',
            'siswaPerTingkat',
            'rombelTanpaAnggota',
            'bukuIndukKurang',
            'trendPerTahun'
        ));
    }
}
