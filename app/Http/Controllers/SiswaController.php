<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class SiswaController extends Controller
{
    public function index()
    {
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        $siswas = Siswa::latest()->paginate(15);
        return view('siswas.index', compact('siswas', 'tahunAktif'));
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        Log::info('Import request received', [
            'has_file' => $request->hasFile('file'),
            'file_is_valid' => $file ? $file->isValid() : 'no file',
            'file_error' => $file ? $file->getError() : 'no file',
            'user' => auth()->user()->id
        ]);



        // Check Role
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            Log::warning('Unauthorized import attempt', ['user' => auth()->user()->email]);
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk melakukan import data.');
        }

        $request->validate([
            'file' => 'required'
        ]);

        // Check Active Tahun Pelajaran
        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Gagal melakukan import: Tidak ada Tahun Pelajaran yang aktif. Silakan aktifkan tahun pelajaran terlebih dahulu di menu Tahun Pelajaran.');
        }

        try {
            $filename = time() . '_' . $file->getClientOriginalName();
            $tempDir = storage_path('app/private/temp');
            
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $file->move($tempDir, $filename);
            $path = 'temp/' . $filename;
            
            $import = new SiswaImport;
            Excel::import($import, $path);
            
            Storage::delete($path);

            $message = "Import Dapodik berhasil diselesaikan. ";
            $message .= "Resume: {$import->createdCount} Siswa Baru ditambahkan, ";
            $message .= "{$import->updatedCount} Siswa diperbarui.";
            
            return redirect()->route('siswas.index')->with('success', $message);
        } catch (\Exception $e) {
            // Clean up if something went wrong but we have a path
            if (isset($path)) {
                Storage::delete($path);
            }
            
            Log::error('Import failed: ' . $e->getMessage(), [
                'exception' => $e,
                'user' => auth()->user()->id
            ]);
            return redirect()->back()->with('error', 'Gagal melakukan import: ' . $e->getMessage());
        }

    }

    public function show(Siswa $siswa)
    {
        return view('siswas.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }
        
        return view('siswas.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:10',
            'nik'  => 'nullable|string|max:16',
            // Add other core fields as needed
        ]);

        $siswa->update($validated);

        return redirect()->route('siswas.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $siswa->delete();
        return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
