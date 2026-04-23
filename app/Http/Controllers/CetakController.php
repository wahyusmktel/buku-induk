<?php

namespace App\Http\Controllers;

// ROUTES NEEDED:
// GET /cetak/surat-aktif/{siswa}          -> CetakController@suratKeteranganAktif  -> name: cetak.surat-aktif
// GET /cetak/surat-lulus/{nisn}           -> CetakController@suratLulus            -> name: cetak.surat-lulus
// GET /cetak/leger/{rombelId}             -> CetakController@leger                  -> name: cetak.leger
// GET /cetak/template-absensi/{rombelId}  -> CetakController@templateAbsensi       -> name: cetak.template-absensi

use App\Exports\AbsensiTemplateExport;
use App\Models\BukuInduk;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\Setting;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CetakController extends Controller
{
    /**
     * Surat Keterangan Aktif Sekolah untuk siswa tertentu.
     *
     * GET /cetak/surat-aktif/{siswa}
     * Gunakan ?preview=1 untuk tampil HTML. Default: stream PDF.
     */
    public function suratKeteranganAktif(string $siswaId)
    {
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->with(['rombel', 'tahunPelajaran'])
            ->findOrFail($siswaId);

        $settings = Setting::pluck('value', 'key')->toArray();

        if (request('preview')) {
            return view('cetak.surat-aktif', compact('siswa', 'settings'));
        }

        $is_pdf = true;
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.surat-aktif', compact('siswa', 'settings', 'is_pdf'))
            ->setOption('isPhpEnabled', true)
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Surat_Aktif_{$siswa->nisn}_{$siswa->nama}.pdf");
    }

    /**
     * Surat Keterangan Lulus untuk siswa berdasarkan NISN.
     *
     * GET /cetak/surat-lulus/{nisn}
     * Gunakan ?preview=1 untuk tampil HTML. Default: stream PDF.
     */
    public function suratLulus(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->where('status', 'Lulus')
            ->with(['rombel', 'tahunPelajaran'])
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $settings = Setting::pluck('value', 'key')->toArray();

        if (request('preview')) {
            return view('cetak.surat-lulus', compact('siswa', 'bukuInduk', 'settings'));
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.surat-lulus', compact('siswa', 'bukuInduk', 'settings'))
            ->setOption('isPhpEnabled', true)
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Surat_Lulus_{$nisn}_{$siswa->nama}.pdf");
    }

    /**
     * Leger Nilai untuk satu Rombel.
     *
     * GET /cetak/leger/{rombelId}
     * Query params opsional:
     *   ?semester=1|2  (default: 1)
     *   ?preview=1     untuk HTML, default: stream PDF
     */
    public function leger(string $rombelId)
    {
        $semester = (int) request('semester', 1);

        $rombel = Rombel::with(['tahunPelajaran', 'siswas'])->findOrFail($rombelId);

        $mataPelajarans = MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        $settings = Setting::pluck('value', 'key')->toArray();

        // Untuk setiap siswa di rombel, ambil data prestasi belajar (kelas = rombel.tingkat, semester)
        $dataSiswa = $rombel->siswas->map(function (Siswa $siswa) use ($rombel, $semester, $mataPelajarans) {
            $bukuInduk = null;
            $prestasi  = null;
            $nilaiMap  = collect();

            if ($siswa->nisn) {
                $bukuInduk = BukuInduk::where('nisn', $siswa->nisn)->first();

                if ($bukuInduk) {
                    $prestasi = $bukuInduk->prestasis()
                        ->with('nilais.mataPelajaran')
                        ->where('kelas', $rombel->tingkat)
                        ->where('semester', $semester)
                        ->first();

                    if ($prestasi) {
                        $nilaiMap = $prestasi->nilais->keyBy('mata_pelajaran_id');
                    }
                }
            }

            return [
                'siswa'    => $siswa,
                'prestasi' => $prestasi,
                'nilaiMap' => $nilaiMap,
            ];
        });

        if (request('preview')) {
            return view('cetak.leger', compact('rombel', 'dataSiswa', 'mataPelajarans', 'settings', 'semester'));
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cetak.leger', compact('rombel', 'dataSiswa', 'mataPelajarans', 'settings', 'semester'))
            ->setOption('isPhpEnabled', true)
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Leger_{$rombel->nama}_Semester{$semester}.pdf");
    }

    /**
     * Download template absensi Excel untuk satu Rombel.
     *
     * GET /cetak/template-absensi/{rombelId}
     */
    public function templateAbsensi(string $rombelId)
    {
        $rombel = Rombel::with([
            'siswas'       => fn($q) => $q->withoutGlobalScope('tahun_aktif')->orderBy('nama'),
            'tahunPelajaran',
        ])->findOrFail($rombelId);

        $filename = 'absensi-' . str_replace(['/', ' ', '\\'], '-', $rombel->nama) . '.xlsx';

        return Excel::download(new AbsensiTemplateExport($rombel), $filename);
    }
}
