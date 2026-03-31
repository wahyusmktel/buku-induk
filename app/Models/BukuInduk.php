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
        'tgl_keluar', 'alasan_keluar', 'tgl_lulus', 'no_ijazah', 'lanjut_ke', 'beasiswa',
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
    ];

    protected $casts = [
        'tgl_masuk_sekolah' => 'date',
        'tgl_pindah_masuk' => 'date',
        'tgl_keluar' => 'date',
        'tgl_lulus' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
    ];

    /**
     * Get the student's Dapodik record (most recent/active one by NISN).
     */
    public function siswa()
    {
        return Siswa::withoutGlobalScope('tahun_aktif')
            ->where('nisn', $this->nisn)
            ->orderBy('created_at', 'desc')
            ->first();
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
        // ── Group A: Field eksklusif di tabel buku_induks (diisi manual) ──
        $bukuIndukFields = [
            'no_induk',                 // Nomor Induk Sekolah
            'nama_panggilan',           // Nama Panggilan
            'tgl_masuk_sekolah',        // Tanggal Masuk Sekolah
            'asal_masuk_sekolah',       // Asal / SD sebelumnya
            'bertempat_tinggal_dengan', // Tinggal bersama
            // Data Ayah (buku induk)
            'nama_ayah',
            'tempat_lahir_ayah',
            'tanggal_lahir_ayah',
            'agama_ayah',
            'pekerjaan_ayah_bi',
            'pendidikan_ayah_bi',
            // Data Ibu (buku induk)
            'nama_ibu',
            'tempat_lahir_ibu',
            'tanggal_lahir_ibu',
            'agama_ibu',
            'pekerjaan_ibu_bi',
            'pendidikan_ibu_bi',
        ];

        // ── Group B: Field yang bersumber dari Dapodik / Siswa ──
        $siswaFields = [
            'nik',              // NIK siswa
            'tempat_lahir',     // Tempat lahir
            'tanggal_lahir',    // Tanggal lahir
            'agama',            // Agama
            'kewarganegaraan',  // Kewarganegaraan
            'bahasa_sehari_hari', // Bahasa sehari-hari
            'golongan_darah',   // Golongan darah
            'alamat',           // Alamat lengkap
            'telepon',          // No. HP / Telepon
            'sekolah_asal',     // Sekolah asal (TK)
            'no_kk',            // Nomor KK
            'jml_saudara_kandung', // Jumlah saudara kandung
            'nik_ayah',
            'nik_ibu',
        ];

        // Hitung dari BukuInduk
        $filledBi = collect($bukuIndukFields)
            ->filter(fn($f) => !empty($this->$f))
            ->count();

        // Hitung dari Siswa (jika relasi sudah di-attach)
        $siswa = $this->getRelation('siswaPokok');
        $filledSiswa = 0;
        if ($siswa) {
            $filledSiswa = collect($siswaFields)
                ->filter(fn($f) => !empty($siswa->$f))
                ->count();
        }

        $total  = count($bukuIndukFields) + count($siswaFields);
        $filled = $filledBi + $filledSiswa;

        return (int) round(($filled / $total) * 100);
    }
}
