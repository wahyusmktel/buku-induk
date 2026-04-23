<?php

namespace App\Http\Controllers;

use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');
        $perPage = (int) $request->get('per_page', 10);

        // Clamp per_page
        $allowedPerPage = [10, 20, 30, 40, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $query = Ekstrakurikuler::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_ekstrakurikuler', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $ekstrakurikulers = $query->orderBy('nama_ekstrakurikuler')->paginate($perPage)->withQueryString();

        return view('ekstrakurikuler.index', compact('ekstrakurikulers', 'search', 'perPage'));
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

    public function toggleAktif($id)
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);
        $ekskul->update(['is_aktif' => !$ekskul->is_aktif]);

        $status = $ekskul->is_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('ekstrakurikuler.index')->with('success', "Ekstrakurikuler \"{$ekskul->nama_ekstrakurikuler}\" berhasil {$status}.");
    }

    public function destroy($id)
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);
        $ekskul->delete();

        return redirect()->route('ekstrakurikuler.index')->with('success', 'Data Ekstrakurikuler berhasil dihapus.');
    }
}
