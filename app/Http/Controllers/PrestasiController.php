<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\PrestasiBelajar;
use Illuminate\Http\Request;
use App\Exports\PrestasiTemplateExport;
use App\Imports\PrestasiImport;
use Maatwebsite\Excel\Facades\Excel;

class PrestasiController extends Controller
{
    /**
     * Store or update a prestasi record for a given buku induk.
     */
    public function store(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $validated = $request->validate([
            'kelas'                => 'required|integer|between:1,6',
            'semester'             => 'required|integer|between:1,2',
            'tahun_pelajaran'      => 'required|string|max:20',
            'nilai'                => 'nullable|array',
            'nilai.*'              => 'nullable|numeric|between:0,100',
            'peringkat'            => 'nullable|integer|min:1',
            'sikap'                => 'nullable|string|max:50',
            'kerajinan'            => 'nullable|string|max:50',
            'kebersihan_kerapian'  => 'nullable|string|max:50',
            'hadir_sakit'          => 'nullable|integer|min:0',
            'hadir_izin'           => 'nullable|integer|min:0',
            'hadir_alpha'          => 'nullable|integer|min:0',
            'keterangan_kenaikan'  => 'nullable|string|max:50',
            'tgl_keputusan_kenaikan' => 'nullable|date',
            'catatan_guru'         => 'nullable|string',
            'ekstrakurikuler'      => 'nullable|array',
            'ekstrakurikuler.*'    => 'nullable|string|max:50',
        ]);

        $validated['buku_induk_id'] = $bukuInduk->id;

        $prestasi = PrestasiBelajar::updateOrCreate(
            [
                'buku_induk_id' => $bukuInduk->id,
                'kelas'         => $validated['kelas'],
                'semester'      => $validated['semester'],
            ],
            \Illuminate\Support\Arr::except($validated, ['nilai'])
        );

        if (!empty($validated['nilai'])) {
            foreach ($validated['nilai'] as $mapelId => $score) {
                if (!empty($score) || $score === '0' || $score === 0) {
                    \App\Models\PrestasiNilai::updateOrCreate(
                        [
                            'prestasi_belajar_id' => $prestasi->id,
                            'mata_pelajaran_id' => $mapelId,
                        ],
                        ['nilai' => $score]
                    );
                } else {
                    \App\Models\PrestasiNilai::where('prestasi_belajar_id', $prestasi->id)
                        ->where('mata_pelajaran_id', $mapelId)
                        ->delete();
                }
            }
        }

        if (array_key_exists('ekstrakurikuler', $validated)) {
            // Hapus yang lama pada kelas dan smt yang sama
            \App\Models\PrestasiEkstrakurikuler::where('siswa_id', $bukuInduk->siswa_id)
                ->where('kelas', $validated['kelas'])
                ->where('semester', $validated['semester'])
                ->delete();

            if (!empty($validated['ekstrakurikuler'])) {
                foreach ($validated['ekstrakurikuler'] as $eksId => $predikat) {
                    if (!empty(trim($predikat))) {
                        \App\Models\PrestasiEkstrakurikuler::create([
                            'siswa_id' => $bukuInduk->siswa_id,
                            'ekstrakurikuler_id' => $eksId,
                            'kelas' => $validated['kelas'],
                            'semester' => $validated['semester'],
                            'predikat' => trim($predikat)
                        ]);
                    }
                }
            }
        }
        
        $prestasi->recalculateTotals();

        return redirect()->route('buku-induk.show', ['nisn' => $nisn, 'tab' => 'akademik'])
            ->with('success', "Data prestasi Kelas {$validated['kelas']} Semester {$validated['semester']} berhasil disimpan.");
    }

    /**
     * Destroy a prestasi record.
     */
    public function destroy(string $nisn, PrestasiBelajar $prestasi)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        if ($prestasi->buku_induk_id !== $bukuInduk->id) {
            abort(403);
        }

        $prestasi->delete();
        return redirect()->route('buku-induk.show', $nisn)->with('success', 'Data prestasi berhasil dihapus.');
    }

    /**
     * Download Template Excel for Prestasi
     */
    public function downloadTemplate(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();
        return Excel::download(new PrestasiTemplateExport($bukuInduk), 'template-nilai-semester-' . $nisn . '.xlsx');
    }

    /**
     * Import Prestasi from Excel
     */
    public function import(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $filename = time() . '_' . $request->file('file')->getClientOriginalName();
            $tempDir = storage_path('app/private/temp');
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $request->file('file')->move($tempDir, $filename);
            $path = 'temp/' . $filename;
            
            $import = new PrestasiImport($bukuInduk);
            Excel::import($import, $path);
            
            // Hapus file setelah import selesai
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);

            return redirect()->route('buku-induk.show', $nisn)
                ->with('success', "Berhasil mengimport {$import->successCount} data nilai semester.");
        } catch (\Exception $e) {
            if (isset($path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
            }
            \Log::error('Import Excel Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat mengolah file: ' . $e->getMessage()]);
        }
    }
}
