<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuInduk extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'nisn', 'no_induk',
        'nama_panggilan', 'kewarganegaraan', 'bahasa_sehari_hari', 'golongan_darah',
        'riwayat_penyakit', 'jml_saudara_tiri', 'jml_saudara_angkat', 'bertempat_tinggal_dengan',
        'tgl_masuk_sekolah', 'asal_masuk_sekolah', 'nama_tk_asal',
        'pindah_dari', 'kelas_pindah_masuk', 'tgl_pindah_masuk',
        'tgl_keluar', 'alasan_keluar', 'tgl_lulus', 'no_ijazah', 'tanggal_ijazah', 'lanjut_ke', 'beasiswa',
        // Data Ayah
        'nama_ayah',
        'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'agama_ayah', 'kewarganegaraan_ayah', 'alamat_ayah',
        'pekerjaan_ayah_bi', 'pendidikan_ayah_bi',
        // Data Ibu
        'nama_ibu',
        'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'agama_ibu', 'kewarganegaraan_ibu', 'alamat_ibu',
        'pekerjaan_ibu_bi', 'pendidikan_ibu_bi',
        // Data Wali
        'nama_wali_bi', 'hubungan_wali', 'pekerjaan_wali_bi', 'pendidikan_wali_bi', 'alamat_wali_bi', 'telp_wali_bi',
        'foto_1', 'foto_2',
    ];

    protected $casts = [
        'tgl_masuk_sekolah' => 'date',
        'tgl_pindah_masuk' => 'date',
        'tgl_keluar' => 'date',
        'tgl_lulus' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
    ];

    public function siswa()
    {
        return Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $this->nisn)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get all historical records for this student across academic years.
     */
    public function riwayatSiswa()
    {
        return Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $this->nisn)
            ->with(['tahunPelajaran', 'rombel'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all academic records (prestasi) for this buku induk.
     */
    public function prestasis()
    {
        return $this->hasMany(PrestasiBelajar::class)->orderBy('kelas')->orderBy('semester');
    }

    /**
     * Siswa data record attached from the controller (for merged completeness).
     * Set via $bi->setRelation('siswaPokok', $siswaRow) in the controller.
     */
    public function siswaPokok()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    /**
     * Get completion percentage of this buku induk.
     *
     * Kelengkapan dihitung dari gabungan field Buku Induk (pengisian manual)
     * DAN field Data Pokok Siswa (Dapodik) yang sudah terintegrasi.
     * Total = 30 field penting, skor 0–100%.
     */
    public function getKelengkapanAttribute(): int
    {
        $siswa = $this->siswaPokok;
        if (!$siswa) return 0;

        $filled = 0;
        $total  = 0;

        // ── 1. Identitas Murid (dari tabel siswas) — 10 field ──
        $identitasFields = [
            'nipd', 'nisn', 'nik', 'nama', 'nama_panggilan',
            'jk', 'tempat_lahir', 'tanggal_lahir', 'agama', 'kewarganegaraan',
        ];
        $total += count($identitasFields);
        $filled += collect($identitasFields)->filter(fn($f) => !empty($siswa->$f))->count();

        // ── 2. Data Periodik (dari tabel data_periodik_siswas) — 7 field ──
        $periodikFields = [
            'jml_saudara_kandung', 'jml_saudara_tiri', 'jml_saudara_angkat',
            'bahasa_sehari_hari', 'alamat_tinggal', 'bertempat_tinggal_pada',
            'jarak_tempat_tinggal_ke_sekolah',
        ];
        $total += count($periodikFields);
        $periodik = $siswa->dataPeriodik;
        if ($periodik) {
            $filled += collect($periodikFields)->filter(fn($f) => !empty($periodik->$f))->count();
        }

        // ── 3. Keadaan Jasmani (dari tabel keadaan_jasmani_siswas) — 5 field ──
        $jasmaniFields = [
            'berat_badan', 'tinggi_badan', 'golongan_darah',
            'nama_riwayat_penyakit', 'kelainan_jasmani',
        ];
        $total += count($jasmaniFields);
        $jasmani = $siswa->keadaanJasmani;
        if ($jasmani) {
            $filled += collect($jasmaniFields)->filter(fn($f) => !empty($jasmani->$f))->count();
        }

        // ── 4. Data Orang Tua — Ayah (3 field) + Ibu (3 field) = 6 field ──
        $orangTuaFields = ['nama', 'pendidikan_terakhir', 'pekerjaan'];
        $total += count($orangTuaFields) * 2; // ayah + ibu

        $orangTua = $siswa->dataOrangTua;
        if ($orangTua) {
            $ayah = $orangTua->where('jenis', 'Ayah')->first();
            $ibu  = $orangTua->where('jenis', 'Ibu')->first();
            if ($ayah) {
                $filled += collect($orangTuaFields)->filter(fn($f) => !empty($ayah->$f))->count();
            }
            if ($ibu) {
                $filled += collect($orangTuaFields)->filter(fn($f) => !empty($ibu->$f))->count();
            }
        }

        // ── 5. Pendidikan Sebelumnya (dari tabel buku_induks) — 3 field ──
        $pendidikanFields = [
            'asal_masuk_sekolah', 'nama_tk_asal', 'tgl_masuk_sekolah',
        ];
        $total += count($pendidikanFields);
        $filled += collect($pendidikanFields)->filter(fn($f) => !empty($this->$f))->count();

        // ── 6. Foto (dari tabel buku_induks) — 1 field ──
        $total += 1;
        if (!empty($this->foto_1)) $filled += 1;

        if ($total === 0) return 0;

        return (int) round(($filled / $total) * 100);
    }
}
