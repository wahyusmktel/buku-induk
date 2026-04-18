<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index(Request $request)
    {
        $trashed = Siswa::onlyTrashed()
            ->withoutGlobalScope('tahun_aktif')
            ->with(['rombel', 'tahunPelajaran'])
            ->when($request->q, fn($q, $search) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%"))
            ->orderBy('deleted_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('trash.index', compact('trashed'));
    }

    public function restore(string $id)
    {
        $siswa = Siswa::onlyTrashed()
            ->withoutGlobalScope('tahun_aktif')
            ->findOrFail($id);
        $siswa->restore();
        return redirect()->back()->with('success', 'Siswa "' . $siswa->nama . '" berhasil dipulihkan.');
    }

    public function forceDelete(string $id)
    {
        $siswa = Siswa::onlyTrashed()
            ->withoutGlobalScope('tahun_aktif')
            ->findOrFail($id);
        $siswa->forceDelete();
        return redirect()->back()->with('success', 'Data siswa berhasil dihapus permanen.');
    }
}
