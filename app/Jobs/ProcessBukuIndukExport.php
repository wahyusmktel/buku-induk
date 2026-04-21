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
    protected $exportType; // 'buku_induk' | 'prestasi'

    public function __construct(ExportJob $exportJob, ?string $tahunId, ?string $rombelId, string $exportType = 'buku_induk')
    {
        $this->exportJob  = $exportJob;
        $this->tahunId    = $tahunId;
        $this->rombelId   = $rombelId;
        $this->exportType = $exportType;
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
                $latestSub->where('rombel_id', $this->rombelId);
            }

            $latestSub->groupBy('nisn');

            // Join kembali untuk mendapatkan instance siswa utama
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
            $ekstrakurikulers = \App\Models\Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();

            // Ambil settings SEKALI untuk seluruh batch (efisiensi)
            $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

            // === Pre-process gambar via GD agar DomPDF bisa render berwarna (fix PNG grayscale bug) ===
            $imageKeys = ['sekolah_kop', 'kepsek_ttd', 'sekolah_stempel'];
            $tempDir = storage_path('app/public/settings/_pdf_temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            foreach ($imageKeys as $key) {
                if (!empty($settings[$key])) {
                    $srcPath = storage_path('app/public/' . $settings[$key]);
                    if (file_exists($srcPath)) {
                        $ext = strtolower(pathinfo($srcPath, PATHINFO_EXTENSION));
                        $src = null;
                        if ($ext === 'png') {
                            $src = @imagecreatefrompng($srcPath);
                        } elseif (in_array($ext, ['jpg', 'jpeg'])) {
                            $src = @imagecreatefromjpeg($srcPath);
                        }
                        if ($src) {
                            $w      = imagesx($src);
                            $h      = imagesy($src);
                            $canvas = imagecreatetruecolor($w, $h);
                            $white  = imagecolorallocate($canvas, 255, 255, 255);
                            imagefill($canvas, 0, 0, $white);
                            imagecopy($canvas, $src, 0, 0, 0, 0, $w, $h);

                            $cleanPath = $tempDir . '/' . $key . '.png';
                            imagepng($canvas, $cleanPath, 0);
                            imagedestroy($src);
                            imagedestroy($canvas);

                            $settings[$key . '_pdf'] = $cleanPath;
                        }
                    }
                }
            }

            // Setup Temp Folder
            $tmpPath = storage_path('app/tmp/export_' . $this->exportJob->id);
            if (!File::exists($tmpPath)) {
                File::makeDirectory($tmpPath, 0755, true);
            }

            $processed = 0;

            foreach ($siswas as $idx => $siswa) {
                // Dapatkan Buku Induk
                $bukuInduk = BukuInduk::where('nisn', $siswa->nisn)->first();
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

                $is_pdf = true;
                $paperSize = $settings['paper_size'] ?? 'a4';

                if ($this->exportType === 'prestasi') {
                    // ===== RENDER PRESTASI BELAJAR (Landscape) =====
                    $pdf = Pdf::loadView(
                        'buku-induk.print-prestasi',
                        compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings', 'ekstrakurikulers', 'is_pdf')
                    )->setOption('isPhpEnabled', true)->setOption('isHtml5ParserEnabled', true);

                    if ($paperSize === 'custom') {
                        $width  = ($settings['paper_width'] ?? 210) * 2.83465;
                        $height = ($settings['paper_height'] ?? 297) * 2.83465;
                        $pdf->setPaper([0, 0, $width, $height], 'landscape');
                    } elseif ($paperSize === 'folio') {
                        $pdf->setPaper([0, 0, 612.0, 936.0], 'landscape');
                    } else {
                        $pdf->setPaper($paperSize, 'landscape');
                    }
                } else {
                    // ===== RENDER BUKU INDUK (Portrait) =====
                    $pdf = Pdf::loadView(
                        'buku-induk.print',
                        compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings', 'is_pdf')
                    )->setOption('isPhpEnabled', true)->setOption('isHtml5ParserEnabled', true);

                    if ($paperSize === 'custom') {
                        $width  = ($settings['paper_width'] ?? 210) * 2.83465;
                        $height = ($settings['paper_height'] ?? 297) * 2.83465;
                        $pdf->setPaper([0, 0, $width, $height], 'portrait');
                    } elseif ($paperSize === 'folio') {
                        $pdf->setPaper([0, 0, 612.0, 936.0], 'portrait');
                    } else {
                        $pdf->setPaper($paperSize, 'portrait');
                    }
                }

                // Naming convention: "01. Ahmad.pdf"
                $num      = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                $safeName = preg_replace('/[^a-zA-Z0-9\s-]/', '', $siswa->nama);
                $fileName = "{$num}. {$safeName}.pdf";

                $pdf->save($tmpPath . '/' . $fileName);

                // Update progres setiap 1 record
                $processed++;
                $this->exportJob->update(['processed_records' => $processed]);
            }

            // === Pack to ZIP ===
            $prefix  = $this->exportType === 'prestasi' ? 'Export_Prestasi_' : 'Export_Buku_Induk_';
            $zipName = $prefix . time() . ".zip";
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

            // Bersihkan folder tmp
            File::deleteDirectory($tmpPath);

            $this->exportJob->update([
                'file_path' => 'exports/' . $zipName,
                'status'    => 'completed',
            ]);

        } catch (\Exception $e) {
            Log::error('Export Massal Error: ' . $e->getMessage());
            $this->exportJob->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
