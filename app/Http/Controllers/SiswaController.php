<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::latest()->paginate(15);
        return view('siswas.index', compact('siswas'));
    }

    public function import(Request $request)
    {
        // Check Role
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Operator', 'Tata Usaha'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk melakukan import data.');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('siswas.index')->with('success', 'Data siswa berhasil diimport dari Dapodik.');
        } catch (\Exception $e) {
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
