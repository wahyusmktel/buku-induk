<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\PrestasiBelajar;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BukuIndukController extends Controller
{
    /**
     * Index: List all buku induk records, searchable by name or NISN.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');
        $statusFilter = $request->get('status', 'Aktif');

        // We query through the siswas table (respecting global scope = active year)
        // but also show archived (Lulus) students if requested
        $query = Siswa::withoutGlobalScope('tahun_aktif')
            ->select('nisn', 'nama', 'rombel_saat_ini', 'status', 'tanggal_lahir', 'jk')
            ->whereNotNull('nisn')
            ->groupBy('nisn', 'nama', 'rombel_saat_ini', 'status', 'tanggal_lahir', 'jk');

        if ($statusFilter !== 'Semua') {
            // Get last known status for each NISN
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('nama')->paginate(20)->withQueryString();

        // Build a map of nisn => buku_induk_id for quick linking
        $nisnList = $siswas->pluck('nisn')->filter()->toArray();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisnList)
            ->get()
            ->keyBy('nisn');

        return view('buku-induk.index', compact('siswas', 'bukuIndukMap', 'search', 'statusFilter'));
    }

    /**
     * Show: Display the full Buku Induk for a student.
     */
    public function show(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        // Fetch the canonical siswa record (latest)
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $prestasis = $bukuInduk->prestasis;

        // Build academic grid: kelas 1-6, semester 1-2
        $akademikGrid = [];
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        return view('buku-induk.show', compact('bukuInduk', 'siswa', 'akademikGrid'));
    }

    /**
     * Edit: Show the edit form.
     */
    public function edit(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        return view('buku-induk.edit', compact('bukuInduk', 'siswa'));
    }

    /**
     * Update: Save buku induk fields.
     */
    public function update(Request $request, string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $validated = $request->validate([
            'no_induk'                => 'nullable|string|max:50',
            'nama_panggilan'          => 'nullable|string|max:100',
            'kewarganegaraan'         => 'nullable|string|max:50',
            'bahasa_sehari_hari'      => 'nullable|string|max:100',
            'golongan_darah'          => 'nullable|string|max:5',
            'riwayat_penyakit'        => 'nullable|string',
            'jml_saudara_tiri'        => 'nullable|integer|min:0',
            'jml_saudara_angkat'      => 'nullable|integer|min:0',
            'bertempat_tinggal_dengan'=> 'nullable|string|max:100',
            'tgl_masuk_sekolah'       => 'nullable|date',
            'asal_masuk_sekolah'      => 'nullable|string|max:100',
            'nama_tk_asal'            => 'nullable|string|max:200',
            'pindah_dari'             => 'nullable|string|max:200',
            'kelas_pindah_masuk'      => 'nullable|string|max:20',
            'tgl_pindah_masuk'        => 'nullable|date',
            'tgl_keluar'              => 'nullable|date',
            'alasan_keluar'           => 'nullable|string|max:255',
            'tgl_lulus'               => 'nullable|date',
            'no_ijazah'               => 'nullable|string|max:100',
            'lanjut_ke'               => 'nullable|string|max:200',
            'beasiswa'                => 'nullable|string',
            'tempat_lahir_ayah'       => 'nullable|string|max:100',
            'tanggal_lahir_ayah'      => 'nullable|date',
            'agama_ayah'              => 'nullable|string|max:50',
            'kewarganegaraan_ayah'    => 'nullable|string|max:50',
            'alamat_ayah'             => 'nullable|string',
            'tempat_lahir_ibu'        => 'nullable|string|max:100',
            'tanggal_lahir_ibu'       => 'nullable|date',
            'agama_ibu'               => 'nullable|string|max:50',
            'kewarganegaraan_ibu'     => 'nullable|string|max:50',
            'alamat_ibu'              => 'nullable|string',
            'nama_wali_bi'            => 'nullable|string|max:200',
            'hubungan_wali'           => 'nullable|string|max:100',
            'pekerjaan_wali_bi'       => 'nullable|string|max:100',
            'pendidikan_wali_bi'      => 'nullable|string|max:100',
            'alamat_wali_bi'          => 'nullable|string',
            'telp_wali_bi'            => 'nullable|string|max:20',
        ]);

        $bukuInduk->update($validated);

        return redirect()->route('buku-induk.show', $nisn)->with('success', 'Buku Induk berhasil diperbarui.');
    }

    /**
     * Print: Render a printable, PDF-ready view.
     */
    public function print(string $nisn)
    {
        $bukuInduk = BukuInduk::where('nisn', $nisn)->firstOrFail();

        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        $prestasis = $bukuInduk->prestasis;

        $akademikGrid = [];
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        return view('buku-induk.print', compact('bukuInduk', 'siswa', 'akademikGrid'));
    }
}
