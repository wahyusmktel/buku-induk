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
                        'siswa_id'           => $siswa->id,
                        'ekstrakurikuler_id' => $ekskulId,
                        'kelas'              => $validated['kelas'],
                        'semester'           => $validated['semester'],
                        'predikat'           => trim($predikat),
                        'keterangan'         => $validated['keterangan'][$ekskulId] ?? null,
                    ]);
                }
            }
        }

        return redirect()
            ->route('buku-induk.edit', ['nisn' => $nisn])
            ->with('success', "Data nilai Ekstrakurikuler Kelas {$validated['kelas']} Semester {$validated['semester']} berhasil disimpan.");
    }

    /**
     * Download the Excel template for Ekstrakurikuler import.
     */
    public function downloadTemplate(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\EkskulTemplateExport($bukuInduk),
            'template-ekskul-' . $nisn . '.xlsx'
        );
    }

    /**
     * Process an uploaded Excel file to import Ekstrakurikuler values.
     */
    public function import(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            $filename = time() . '_ekskul_' . $request->file('file')->getClientOriginalName();
            $tempDir  = storage_path('app/private/temp');

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $request->file('file')->move($tempDir, $filename);
            $path = 'temp/' . $filename;

            $import = new \App\Imports\EkskulImport($bukuInduk);
            \Maatwebsite\Excel\Facades\Excel::import($import, $path);

            // Cleanup temp file
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);

            $msg = "Berhasil mengimport nilai Ekstrakurikuler untuk {$import->successCount} baris data.";
            if (!empty($import->errors)) {
                $msg .= ' Catatan: ' . implode(' | ', array_slice($import->errors, 0, 3));
            }

            return redirect()
                ->route('buku-induk.edit', ['nisn' => $nisn])
                ->with('success', $msg);

        } catch (\Exception $e) {
            if (isset($path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
            }
            \Log::error('Ekskul Import Error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['file' => 'Terjadi kesalahan saat mengolah file: ' . $e->getMessage()]);
        }
    }
}
