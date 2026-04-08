<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

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

        ActivityLogService::log('mapel_add', "Menambahkan Mata Pelajaran baru: {$validated['nama']}", [
            'nama' => $validated['nama']
        ]);

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

        ActivityLogService::log('mapel_update', "Memperbarui Mata Pelajaran: {$mataPelajaran->nama}", [
            'mapel_id' => $mataPelajaran->id,
            'nama' => $mataPelajaran->nama
        ]);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $namaMapel = $mataPelajaran->nama;
        $mataPelajaran->delete();

        ActivityLogService::log('mapel_delete', "Menghapus Mata Pelajaran: {$namaMapel}", [
            'nama' => $namaMapel
        ]);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }
}
