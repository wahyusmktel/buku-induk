<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::orderBy('urutan')->get();
        return view('mata-pelajaran.index', compact('mapels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:mata_pelajarans,nama',
            'kelompok' => 'required|string|max:50',
            'urutan' => 'required|integer',
            'is_aktif' => 'boolean',
        ]);
        
        $validated['is_aktif'] = $request->has('is_aktif');

        MataPelajaran::create($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150|unique:mata_pelajarans,nama,'.$mataPelajaran->id,
            'kelompok' => 'required|string|max:50',
            'urutan' => 'required|integer',
            'is_aktif' => 'boolean',
        ]);
        
        $validated['is_aktif'] = $request->has('is_aktif');

        $mataPelajaran->update($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();
        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
