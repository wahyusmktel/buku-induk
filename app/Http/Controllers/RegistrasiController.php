<?php

namespace App\Http\Controllers;

use App\Models\RegistrasiSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    public function store(Request $request, string $siswaId)
    {
        $request->validate([
            'jenis_registrasi' => 'required|in:Daftar Baru,Mutasi Masuk,Pindah Keluar,Lulus',
            'tanggal'          => 'required|date',
            'tujuan_sekolah'   => 'nullable|string|max:255',
            'tujuan_kelas'     => 'nullable|string|max:50',
            'alasan_catatan'   => 'nullable|string',
        ]);

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')->findOrFail($siswaId);

        $siswa->registrasi()->create([
            'jenis_registrasi' => $request->jenis_registrasi,
            'tanggal'          => $request->tanggal,
            'tujuan_sekolah'   => $request->tujuan_sekolah,
            'tujuan_kelas'     => $request->tujuan_kelas,
            'alasan_catatan'   => $request->alasan_catatan,
        ]);

        return redirect()->back()->with('success', 'Data registrasi berhasil ditambahkan.');
    }

    public function destroy(string $siswaId, RegistrasiSiswa $registrasi)
    {
        abort_if($registrasi->siswa_id !== $siswaId, 403, 'Data registrasi tidak ditemukan untuk siswa ini.');

        $registrasi->delete();

        return redirect()->back()->with('success', 'Data registrasi berhasil dihapus.');
    }
}
