<?php

namespace App\Http\Controllers;

use App\Models\BukuInduk;
use App\Models\PrestasiBelajar;
use App\Models\Siswa;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogService;

class BukuIndukController extends Controller
{
    /**
     * Index: List all buku induk records, searchable by name or NISN.
     */
    public function index(Request $request)
    {
        $search       = $request->get('q', '');
        $statusFilter = $request->get('status', 'Aktif');
        $tahunId      = $request->get('tahun_id', '');
        $perPage      = (int) $request->get('per_page', 20);

        // Clamp per_page to allowed values
        $allowedPerPage = [10, 20, 30, 40, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 20;
        }

        // All tahun pelajaran for the filter dropdown
        $tahunPelajarans = TahunPelajaran::orderByDesc('tahun')->orderByDesc('semester')->get();

        // ── Build query: satu baris per NISN (terbaru by created_at) ─────
        // UUID primary key tidak bisa di-MAX(), jadi kita pakai MAX(created_at).
        // Subquery menghasilkan: nisn → max created_at dalam scope tahun (jika dipilih).

        $latestSub = Siswa::withoutGlobalScope('tahun_aktif')
            ->selectRaw('nisn AS s_nisn, MAX(created_at) AS max_ca')
            ->whereNotNull('nisn');

        if ($tahunId) {
            $latestSub->where('tahun_pelajaran_id', $tahunId);
        }

        $latestSub->groupBy('nisn');

        $query = Siswa::withoutGlobalScope('tahun_aktif')
            ->joinSub($latestSub, 'ls', function ($join) {
                $join->on('siswas.nisn', '=', 'ls.s_nisn')
                     ->on('siswas.created_at', '=', 'ls.max_ca');
            })
            ->select(
                'siswas.nisn', 'siswas.nama', 'siswas.rombel_saat_ini',
                'siswas.status', 'siswas.tanggal_lahir', 'siswas.jk',
                'siswas.tahun_pelajaran_id'
            )
            ->whereNotNull('siswas.nisn');

        if ($statusFilter !== 'Semua') {
            $query->where('siswas.status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('siswas.nama', 'like', "%{$search}%")
                  ->orWhere('siswas.nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('siswas.nama')->paginate($perPage)->withQueryString();

        // Map nisn => BukuInduk record (with kelengkapan accessor)
        $nisnList     = $siswas->pluck('nisn')->filter()->toArray();
        $bukuIndukMap = BukuInduk::whereIn('nisn', $nisnList)->get()->keyBy('nisn');

        // Build kelengkapan that merges Siswa data into the calculation
        // Attach the matched Siswa row onto each BukuInduk so the accessor can use it
        $siswaByNisn  = $siswas->keyBy('nisn');
        foreach ($bukuIndukMap as $nisn => $bi) {
            $bi->setRelation('siswaPokok', $siswaByNisn[$nisn] ?? null);
        }

        return view('buku-induk.index', compact(
            'siswas', 'bukuIndukMap', 'search', 'statusFilter',
            'tahunPelajarans', 'tahunId', 'perPage'
        ));
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

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        // Build academic grid: kelas 1-6, semester 1-2
        $akademikGrid = [];
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }

        return view('buku-induk.show', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans'));
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
            // Ayah
            'nama_ayah'               => 'nullable|string|max:200',
            'tempat_lahir_ayah'       => 'nullable|string|max:100',
            'tanggal_lahir_ayah'      => 'nullable|date',
            'agama_ayah'              => 'nullable|string|max:50',
            'pekerjaan_ayah_bi'       => 'nullable|string|max:100',
            'pendidikan_ayah_bi'      => 'nullable|string|max:100',
            'kewarganegaraan_ayah'    => 'nullable|string|max:50',
            'alamat_ayah'             => 'nullable|string',
            // Ibu
            'nama_ibu'                => 'nullable|string|max:200',
            'tempat_lahir_ibu'        => 'nullable|string|max:100',
            'tanggal_lahir_ibu'       => 'nullable|date',
            'agama_ibu'               => 'nullable|string|max:50',
            'pekerjaan_ibu_bi'        => 'nullable|string|max:100',
            'pendidikan_ibu_bi'       => 'nullable|string|max:100',
            'kewarganegaraan_ibu'     => 'nullable|string|max:50',
            'alamat_ibu'              => 'nullable|string',
            // Wali
            'nama_wali_bi'            => 'nullable|string|max:200',
            'hubungan_wali'           => 'nullable|string|max:100',
            'pekerjaan_wali_bi'       => 'nullable|string|max:100',
            'pendidikan_wali_bi'      => 'nullable|string|max:100',
            'alamat_wali_bi'          => 'nullable|string',
            'telp_wali_bi'            => 'nullable|string|max:20',
            'foto_1'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_2'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_1')) {
            if ($bukuInduk->foto_1) Storage::disk('public')->delete($bukuInduk->foto_1);
            
            $file = $request->file('foto_1');
            $filename = time() . '_1_' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/siswa_photos'), $filename);
            $validated['foto_1'] = 'siswa_photos/' . $filename;
        }
        
        if ($request->hasFile('foto_2')) {
            if ($bukuInduk->foto_2) Storage::disk('public')->delete($bukuInduk->foto_2);
            
            $file = $request->file('foto_2');
            $filename = time() . '_2_' . $file->getClientOriginalName();
            $file->move(storage_path('app/public/siswa_photos'), $filename);
            $validated['foto_2'] = 'siswa_photos/' . $filename;
        }

        $bukuInduk->update($validated);

        // Fetch name for logging (from latest Siswa record)
        $siswa = Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $nisn)
            ->orderBy('created_at', 'desc')
            ->first();
        
        $namaSiswa = $siswa ? $siswa->nama : "NISN: {$nisn}";

        ActivityLogService::log('buku_induk_update', "Melengkapi data Buku Induk: {$namaSiswa}", [
            'nisn' => $nisn,
            'nama' => $namaSiswa
        ]);

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

        $prestasis = $bukuInduk->prestasis()->with('nilais.mataPelajaran')->get();
        $mataPelajarans = \App\Models\MataPelajaran::where('is_aktif', true)->orderBy('urutan')->get();

        $akademikGrid = [];
        foreach (range(1, 6) as $kelas) {
            foreach ([1, 2] as $semester) {
                $record = $prestasis->where('kelas', $kelas)->where('semester', $semester)->first();
                $akademikGrid[$kelas][$semester] = $record;
            }
        }
        
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('buku-induk.print', compact('bukuInduk', 'siswa', 'akademikGrid', 'mataPelajarans', 'settings'));
    }
}
