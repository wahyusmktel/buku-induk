<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index()
    {
        $ekstrakurikulers = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();
        return view('ekstrakurikuler.index', compact('ekstrakurikulers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekstrakurikuler' => 'required|string|max:255|unique:ekstrakurikulers',
            'deskripsi' => 'nullable|string'
        ]);

        Ekstrakurikuler::create($request->all());

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);

        $request->validate([
            'nama_ekstrakurikuler' => 'required|string|max:255|unique:ekstrakurikulers,nama_ekstrakurikuler,' . $id,
            'deskripsi' => 'nullable|string'
        ]);

        $ekskul->update($request->all());

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);
        $ekskul->delete();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil dihapus.');
    }
}
