<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunPelajaranController extends Controller
{
    public function index()
    {
        $tahunPelajarans = TahunPelajaran::orderBy('tahun', 'desc')->orderBy('semester', 'desc')->get();
        return view('tahun-pelajaran.index', compact('tahunPelajarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:10', // e.g., 2025/2026
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        TahunPelajaran::create([
            'tahun' => $request->tahun,
            'semester' => $request->semester,
            'is_aktif' => false,
        ]);

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil ditambahkan.');
    }

    public function activate($id)
    {
        DB::transaction(function () use ($id) {
            // Deactivate all
            TahunPelajaran::query()->update(['is_aktif' => false]);
            
            // Activate selected
            $tp = TahunPelajaran::findOrFail($id);
            $tp->update(['is_aktif' => true]);

            // Backfill existing students with null academic year to this active year
            \App\Models\Siswa::withoutGlobalScope('tahun_aktif')
                ->whereNull('tahun_pelajaran_id')
                ->update(['tahun_pelajaran_id' => $tp->id]);
        });

        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil diaktifkan dan data siswa tanpa tahun telah dikaitkan.');
    }

    public function destroy($id)
    {
        $tp = TahunPelajaran::findOrFail($id);
        
        if ($tp->siswas()->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus tahun pelajaran yang sudah memiliki data siswa.');
        }

        $tp->delete();
        return redirect()->back()->with('success', 'Tahun Pelajaran berhasil dihapus.');
    }
}
