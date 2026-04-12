<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use App\Models\PrestasiEkstrakurikuler;
use Illuminate\Http\Request;

class EkskulPrestasiController extends Controller
{
    /**
     * Store or update ekstrakurikuler scores for a given semester.
     */
    public function store(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $validated = $request->validate([
            'kelas'           => 'required|integer|min:1|max:12',
            'semester'        => 'required|integer|between:1,2',
            'ekskul'          => 'nullable|array',
            'ekskul.*'        => 'nullable|string|in:A,B,C,D,',
            'keterangan'      => 'nullable|array',
            'keterangan.*'    => 'nullable|string|max:255',
        ]);

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        // Hapus data ekskul lama untuk kelas + semester ini
        PrestasiEkstrakurikuler::where('siswa_id', $siswa->id)
            ->where('kelas', $validated['kelas'])
            ->where('semester', $validated['semester'])
            ->delete();

        // Insert data baru
        if (!empty($validated['ekskul'])) {
            foreach ($validated['ekskul'] as $ekskulId => $predikat) {
                if (!empty(trim($predikat ?? ''))) {
                    PrestasiEkstrakurikuler::create([
                        'siswa_id'          => $siswa->id,
                        'ekstrakurikuler_id' => $ekskulId,
                        'kelas'             => $validated['kelas'],
                        'semester'          => $validated['semester'],
                        'predikat'          => trim($predikat),
                        'keterangan'        => $validated['keterangan'][$ekskulId] ?? null,
                    ]);
                }
            }
        }

        return redirect()
            ->route('buku-induk.edit', ['nisn' => $nisn])
            ->with('success', "Data nilai Ekstrakurikuler Kelas {$validated['kelas']} Semester {$validated['semester']} berhasil disimpan.");
    }
}
