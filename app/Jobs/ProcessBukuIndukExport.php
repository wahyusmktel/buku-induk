<?php

namespace App\Jobs;

use App\Models\BukuInduk;
use App\Models\ExportJob;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ProcessBukuIndukExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 jam limit eksekusi untuk export massal ini
    
    // Nonaktifkan pengaturan memori laravel khusus job ini, ini render PDF berat
    public $memory = -1;

    protected $exportJob;
    protected $tahunId;
    protected $rombelId;

    public function __construct(ExportJob $exportJob, ?string $tahunId, ?string $rombelId)
    {
        $this->exportJob = $exportJob;
        $this->tahunId = $tahunId;
        $this->rombelId = $rombelId;
    }

    public function handle(): void
    {
        $this->exportJob->update(['status' => 'processing']);

        try {
            // Ambil siswa sesuai filter
            $latestSub = Siswa::withoutGlobalScope('tahun_aktif')
                ->selectRaw('nisn AS s_nisn, MAX(created_at) AS max_ca')
                ->whereNotNull('nisn');

            if ($this->tahunId) {
                $latestSub->where('tahun_pelajaran_id', $this->tahunId);
            }
            if ($this->rombelId) {
                // Relasi rombel ada di siswas via 'rombel_saat_ini' atau 'rombel_id'. Kita harus cek filter yg mana yg benar.
                $latestSub->where('rombel_id', $this->rombelId);
            }

            $latestSub->groupBy('nisn');

            // Kita join kembali untuk mendapatkan instance siswa utama
            $siswas = Siswa::withoutGlobalScope('tahun_aktif')
                ->joinSub($latestSub, 'ls', function ($join) {
                    $join->on('siswas.nisn', '=', 'ls.s_nisn')
                         ->on('siswas.created_at', '=', 'ls.max_ca');
                })
                ->orderBy('siswas.nama', 'asc')
                ->get();

            if ($siswas->isEmpty()) {
                throw new \Exception("Tidak ada data siswa ditemukan untuk kriteria export ini.");
            }

            $totalRecords = $siswas->count();
            $this->exportJob->update(['total_records' => $totalRecords, 'processed_records' => 0]);

            $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

            // Setup Temp Folder
            $tmpPath = storage_path('app/tmp/export_' . $this->exportJob->id);
            if (!File::exists($tmpPath)) {
                File::makeDirectory($tmpPath, 0755, true);
            }

            $processed = 0;

            foreach ($siswas as $idx => $siswa) {
                // Dapatkan Buku Induk nya
                $bukuInduk = BukuInduk::where('nisn', $siswa->nisn)->first();

                // Jika null (belum ada record utama), lewati atau buat skeleton kosong
                if (!$bukuInduk) {
                    $bukuInduk = new BukuInduk();
                }

                $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
                $akademikGrid = [];
                foreach (range(1, 6) as $kelas) {
                    foreach ([1, 2] as $semester) {
                        $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                        $akademikGrid[$kelas][$semester] = $record;
                    }
                }

                // Ambil setting kustom kertas dari database
                $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

                // Render PDF via View (View yang sama dengan mode cetak browser)
                // catatan: karena background job, pastikan asset storage_path jika ada gambar lokal
                $is_pdf = true;
                $pdf = Pdf::loadView('buku-induk.print', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'is_pdf', 'settings'));
                
                // Menentukan Ukuran Kertas
                $paperSize = $settings['paper_size'] ?? 'a4';
                
                if ($paperSize === 'custom') {
                    // Konversi mm ke basis points (1 mm = 2.83465 pt)
                    $width = ($settings['paper_width'] ?? 210) * 2.83465;
                    $height = ($settings['paper_height'] ?? 297) * 2.83465;
                    $pdf->setPaper([0, 0, $width, $height], 'portrait');
                } elseif ($paperSize === 'folio') {
                    // Folio F4 (8.5 x 13 inch -> 612 x 936 pt)
                    $pdf->setPaper([0, 0, 612.00, 936.00], 'portrait');
                } else {
                    $pdf->setPaper($paperSize, 'portrait');
                }
                
                $pdf->setOption('isHtml5ParserEnabled', true);
                
                // Naming convention file: "01. Ahmad.pdf"
                $num = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                $safeName = preg_replace('/[^a-zA-Z0-9\s-]/', '', $siswa->nama);
                $fileName = "{$num}. {$safeName}.pdf";

                $pdf->save($tmpPath . '/' . $fileName);

                // Update progres setiap 1
                $processed++;
                $this->exportJob->update(['processed_records' => $processed]);
            }

            // Pack to ZIP
            $zipName = "Export_Buku_Induk_" . time() . ".zip";
            $zipPath = storage_path('app/public/exports/' . $zipName);
            
            if (!File::exists(storage_path('app/public/exports'))) {
                File::makeDirectory(storage_path('app/public/exports'), 0755, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = File::files($tmpPath);
                foreach ($files as $file) {
                    $zip->addFile($file->getPathname(), $file->getFilename());
                }
                $zip->close();
            } else {
                throw new \Exception("Gagal membuat file ZIP.");
            }

            // Clean tmp
            File::deleteDirectory($tmpPath);

            $this->exportJob->update([
                'file_path' => 'exports/' . $zipName,
                'status' => 'completed'
            ]);

        } catch (\Exception $e) {
            Log::error('Export Massal Error: ' . $e->getMessage());
            $this->exportJob->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
