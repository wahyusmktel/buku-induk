<?php

// ROUTES NEEDED:
//   GET  /laporan                  → [LaporanController::class, 'index']        name: laporan.index
//   POST /laporan/alumni/export    → [LaporanController::class, 'exportAlumni'] name: laporan.alumni.export
//   GET  /laporan/prestasi         → [LaporanController::class, 'prestasi']     name: laporan.prestasi
//   GET  /laporan/alumni           → [LaporanController::class, 'alumni']       name: laporan.alumni

namespace App\Http\Controllers;

use App\Exports\AlumniExport;
use App\Models\BukuInduk;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Tahun pelajaran aktif sebagai konteks utama
        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();

        // ── Statistik dari tahun aktif ──────────────────────────────────────
        $tahunAktifId = $tahunAktif?->id;

        // Siswa per tingkat kelas (tahun aktif, status Aktif)
        $siswaPerTingkat = Siswa::where('tahun_pelajaran_id', $tahunAktifId)
            ->where('status', 'Aktif')
            ->selectRaw('tingkat_kelas, count(*) as total')
            ->groupBy('tingkat_kelas')
            ->orderBy('tingkat_kelas')
            ->withoutGlobalScope('tahun_aktif')
            ->get()
            ->keyBy('tingkat_kelas');

        // Distribusi JK (tahun aktif, status Aktif)
        $siswaPerJK = [
            'L' => Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', $tahunAktifId)
                ->where('status', 'Aktif')
                ->where('jk', 'L')
                ->count(),
            'P' => Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', $tahunAktifId)
                ->where('status', 'Aktif')
                ->where('jk', 'P')
                ->count(),
        ];

        // Rombel per tingkat (tahun aktif)
        $rombelPerTingkat = Rombel::where('tahun_pelajaran_id', $tahunAktifId)
            ->selectRaw('tingkat, count(*) as total')
            ->groupBy('tingkat')
            ->orderBy('tingkat')
            ->get()
            ->keyBy('tingkat');

        // ── Statistik lintas semua tahun (withoutGlobalScope) ───────────────

        // Status siswa (semua tahun)
        $statusCounts = Siswa::withoutGlobalScope('tahun_aktif')
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $siswaPerStatus = [
            'Aktif'          => $statusCounts->get('Aktif', 0),
            'Lulus'          => $statusCounts->get('Lulus', 0),
            'Keluar/Mutasi'  => $statusCounts->get('Keluar/Mutasi', 0),
        ];

        // Trend per tahun pelajaran
        $trendPerTahun = TahunPelajaran::orderBy('tahun')->orderBy('semester')->get()
            ->map(function (TahunPelajaran $tp) {
                $base = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('tahun_pelajaran_id', $tp->id);

                return [
                    'tahun'    => $tp->tahun,
                    'semester' => $tp->semester,
                    'total'    => (clone $base)->count(),
                    'baru'     => (clone $base)->where('status', 'Aktif')->count(),
                    'lulus'    => (clone $base)->where('status', 'Lulus')->count(),
                ];
            });

        // ── Buku Induk ───────────────────────────────────────────────────────
        $totalBukuInduk    = BukuInduk::count();
        $bukuIndukLengkap  = BukuInduk::whereNotNull('foto_1')->count();

        // ── Filter & list tahun untuk dropdown ──────────────────────────────
        $tahunList = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();

        return view('laporan.statistik', compact(
            'tahunAktif',
            'siswaPerTingkat',
            'siswaPerJK',
            'rombelPerTingkat',
            'siswaPerStatus',
            'trendPerTahun',
            'totalBukuInduk',
            'bukuIndukLengkap',
            'tahunList',
        ));
    }

    public function exportAlumni(Request $request)
    {
        $query = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('status', 'Lulus')
            ->with('tahunPelajaran');

        if ($request->filled('tahun_id')) {
            $query->where('tahun_pelajaran_id', $request->tahun_id);
        }

        $alumni = $query->orderBy('nama')->get();

        return Excel::download(new AlumniExport($alumni), 'alumni.xlsx');
    }

    public function prestasi(Request $request)
    {
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->get();
        $tahunAktif      = $tahunPelajarans->firstWhere('is_aktif', true);
        $tahunId         = $request->tahun_id ?? $tahunAktif?->id;
        $tahunDipilih    = $tahunPelajarans->firstWhere('id', $tahunId);

        $rombelId = $request->rombel_id;
        $search   = $request->q;

        // Rombel dropdown options
        $rombels = Rombel::where('tahun_pelajaran_id', $tahunId)
            ->withCount(['siswas as jumlah_siswa' => fn($q) => $q->withoutGlobalScope('tahun_aktif')])
            ->orderBy('nama')
            ->get();

        $selectedRombel = null;
        $siswaData = collect();
        $siswaPaginated = null;

        if ($rombelId) {
            $selectedRombel = $rombels->firstWhere('id', $rombelId);
            if ($selectedRombel) {
                // Fetch students inside the rombel with pagination and search
                $siswaQuery = Siswa::withoutGlobalScope('tahun_aktif')
                    ->where('rombel_id', $rombelId);

                if ($search) {
                    $siswaQuery->where(function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%")
                            ->orWhere('nipd', 'like', "%{$search}%");
                    });
                }

                $siswaPaginated = $siswaQuery->orderBy('nama')
                    ->paginate(10)
                    ->withQueryString();

                $siswaData = collect($siswaPaginated->items())->map(function (Siswa $siswa) use ($selectedRombel) {
                    $prestasiList = collect();
                    if ($siswa->nisn) {
                        $bukuInduk = BukuInduk::where('nisn', $siswa->nisn)->first();
                        if ($bukuInduk) {
                            $prestasiList = $bukuInduk->prestasis()
                                ->where('kelas', $selectedRombel->tingkat)
                                ->orderBy('semester')
                                ->get();
                        }
                    }
                    return [
                        'siswa'    => $siswa,
                        'prestasi' => $prestasiList,
                    ];
                });
            }
        }

        $mataPelajarans = MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        return view('laporan.prestasi', compact(
            'rombels',
            'selectedRombel',
            'siswaData',
            'siswaPaginated',
            'tahunPelajarans',
            'tahunDipilih',
            'mataPelajarans',
            'tahunId',
            'rombelId',
            'search'
        ));
    }

    public function alumni(Request $request)
    {
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->get();
        $tahunId         = $request->tahun_id;

        $alumni = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('status', 'Lulus')
            ->with(['tahunPelajaran', 'rombel'])
            ->when($tahunId, fn($q) => $q->where('tahun_pelajaran_id', $tahunId))
            ->when($request->q, fn($q, $s) => $q->where(function ($sub) use ($s) {
                $sub->where('nama', 'like', "%{$s}%")
                    ->orWhere('nisn', 'like', "%{$s}%");
            }))
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        // Ambil data BukuInduk berdasarkan NISN
        $nisns        = $alumni->pluck('nisn')->filter()->unique()->values();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisns)->get()->keyBy('nisn');

        // Statistik alumni
        $totalAlumni = Siswa::withoutGlobalScope('tahun_aktif')->where('status', 'Lulus')->count();

        $alumniPerTahun = TahunPelajaran::withCount([
            'siswas as alumni_count' => fn($q) => $q->withoutGlobalScope('tahun_aktif')->where('status', 'Lulus'),
        ])
        ->having('alumni_count', '>', 0)
        ->orderBy('tahun', 'desc')
        ->get();

        return view('laporan.alumni', compact(
            'alumni',
            'tahunPelajarans',
            'tahunId',
            'bukuIndukMap',
            'totalAlumni',
            'alumniPerTahun',
        ));
    }
}
