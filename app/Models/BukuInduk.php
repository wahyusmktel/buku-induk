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
        'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'agama_ayah', 'kewarganegaraan_ayah', 'alamat_ayah',
        'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'agama_ibu', 'kewarganegaraan_ibu', 'alamat_ibu',
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
     * Get completion percentage of this buku induk.
     */
    public function getKelengkapanAttribute(): int
    {
        $fields = [
            'no_induk', 'nama_panggilan', 'kewarganegaraan', 'bahasa_sehari_hari',
            'golongan_darah', 'bertempat_tinggal_dengan', 'tgl_masuk_sekolah',
            'asal_masuk_sekolah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah',
            'agama_ayah', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'agama_ibu',
        ];

        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }
}
