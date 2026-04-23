<?php

namespace App\Http\Controllers;

use App\Exports\EkstrakurikulerMasterTemplateExport;
use App\Imports\EkstrakurikulerMasterImport;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function downloadTemplate()
    {
        return Excel::download(new EkstrakurikulerMasterTemplateExport(), 'template_ekstrakurikuler.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:4096',
        ], [
            'file_excel.required' => 'File Excel wajib dipilih.',
            'file_excel.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file_excel.max'      => 'Ukuran file maksimal 4 MB.',
        ]);

        $import = new EkstrakurikulerMasterImport();
        Excel::import($import, $request->file('file_excel'));

        $msg = "Import selesai: {$import->successCount} data berhasil ditambahkan";
        if ($import->skipCount > 0) {
            $msg .= ", {$import->skipCount} baris dilewati (duplikat/kosong)";
        }
        $msg .= '.';

        return redirect()->route('ekstrakurikuler.index')->with('success', $msg);
    }
}
