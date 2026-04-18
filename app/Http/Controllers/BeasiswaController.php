<?php

namespace App\Http\Controllers;

use App\Models\BeasiswaSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class BeasiswaController extends Controller
{
    public function store(Request $request, string $siswaId)
    {
        $request->validate([
            'jenis_beasiswa' => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'tahun'          => 'required|string|max:10',
        ]);

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')->findOrFail($siswaId);

        $siswa->beasiswa()->create([
            'jenis_beasiswa' => $request->jenis_beasiswa,
            'keterangan'     => $request->keterangan,
            'tahun'          => $request->tahun,
        ]);

        return redirect()->back()->with('success', 'Data beasiswa berhasil ditambahkan.');
    }

    public function destroy(string $siswaId, BeasiswaSiswa $beasiswa)
    {
        abort_if($beasiswa->siswa_id !== $siswaId, 403, 'Data beasiswa tidak ditemukan untuk siswa ini.');

        $beasiswa->delete();

        return redirect()->back()->with('success', 'Data beasiswa berhasil dihapus.');
    }
}
