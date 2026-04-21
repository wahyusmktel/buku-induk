<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessBukuIndukExport;
use App\Models\ExportJob;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        $tahunPelajarans = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();
        $rombels = \App\Models\Rombel::withCount('siswas')->orderBy('nama', 'asc')->get();
        
        // Build Export Jobs Query
        $query = ExportJob::orderBy('created_at', 'desc');

        // Filter Nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter Tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Paginate 10 data
        $exportJobs = $query->paginate(10)->withQueryString();

        return view('exports.index', compact('tahunPelajarans', 'rombels', 'exportJobs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'export_type' => 'required|in:buku_induk,prestasi',
            'tahun_id'    => 'nullable|exists:tahun_pelajarans,id',
            'rombel_id'   => 'nullable',
        ]);

        $exportType = $request->export_type ?? 'buku_induk';

        $job = ExportJob::create([
            'name'        => $request->name,
            'export_type' => $exportType,
            'status'      => 'pending',
        ]);

        // Dispatch job ke Redis queue
        ProcessBukuIndukExport::dispatch($job, $request->tahun_id, $request->rombel_id, $exportType);

        $label = $exportType === 'prestasi' ? 'Prestasi Belajar' : 'Buku Induk';
        ActivityLogService::log('mass_export_start', "Memulai Export Massal {$label}: {$request->name}", [
            'name'        => $request->name,
            'export_type' => $exportType,
            'tahun_id'    => $request->tahun_id,
            'rombel_id'   => $request->rombel_id,
        ]);

        return response()->json([
            'success' => true,
            'job_id'  => $job->id,
            'message' => 'Proses export dimasukkan ke dalam antrian.',
        ]);
    }

    public function progress($id)
    {
        $job = ExportJob::findOrFail($id);
        
        $percentage = 0;
        if ($job->total_records > 0) {
            $percentage = round(($job->processed_records / $job->total_records) * 100);
        }

        return response()->json([
            'status' => $job->status,
            'percentage' => $percentage,
            'processed' => $job->processed_records,
            'total' => $job->total_records,
            'error_message' => $job->error_message
        ]);
    }

    public function download($id)
    {
        $job = ExportJob::findOrFail($id);

        if ($job->status !== 'completed' || !$job->file_path) {
            abort(404, 'File export tidak ditemukan atau belum selesai.');
        }

        $path = storage_path('app/public/' . $job->file_path);
        
        if (!file_exists($path)) {
            abort(404, 'File ZIP sudah dihapus dari server.');
        }

        return response()->download($path);
    }

    public function destroy($id)
    {
        $job = ExportJob::findOrFail($id);

        if ($job->file_path && file_exists(storage_path('app/public/' . $job->file_path))) {
            unlink(storage_path('app/public/' . $job->file_path));
        }

        $job->delete();

        return redirect()->route('exports.index')->with('success', 'Histori export berhasil dihapus.');
    }
}
