<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\BukuInduk;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('q', '');
        $perPage  = (int) $request->get('per_page', 20);
        $tahunId  = $request->get('tahun_id', '');

        $allowedPerPage = [10, 20, 30, 40, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }

        // Ambil semua tahun pelajaran untuk filter
        $tahunList = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();

        // Query alumni: siswa dengan status Lulus di semua tahun (tanpa global scope)
        $query = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('status', 'Lulus');

        if ($tahunId) {
            $query->where('tahun_pelajaran_id', $tahunId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nipd', 'like', "%{$search}%");
            });
        }

        $alumni = $query->with('tahunPelajaran')
            ->orderBy('nama', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Map nisn => BukuInduk
        $nisnList     = $alumni->pluck('nisn')->filter()->toArray();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisnList)->get()->keyBy('nisn');

        return view('alumni.index', compact(
            'alumni', 'bukuIndukMap', 'search', 'perPage', 'tahunList', 'tahunId'
        ));
    }
}
