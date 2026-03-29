<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'tahun_pelajaran_id',
        'rombel_id',
        'nama', 'nipd', 'jk', 'nisn', 'tempat_lahir', 'tanggal_lahir', 'nik', 'agama',
        'alamat', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos',
        'jenis_tinggal', 'alat_transportasi', 'telepon', 'hp', 'email',
        'skhun', 'penerima_kps', 'no_kps',
        'nama_ayah', 'tahun_lahir_ayah', 'jenjang_pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nik_ayah',
        'nama_ibu', 'tahun_lahir_ibu', 'jenjang_pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'nik_ibu',
        'nama_wali', 'tahun_lahir_wali', 'jenjang_pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali', 'nik_wali',
        'rombel_saat_ini', 'no_peserta_un', 'no_seri_ijazah', 'penerima_kip', 'nomor_kip', 'nama_di_kip', 'nomor_kks', 'no_registrasi_akta_lahir',
        'bank', 'nomor_rekening_bank', 'rekening_atas_nama',
        'layak_pip', 'alasan_layak_pip',
        'kebutuhan_khusus', 'sekolah_asal', 'anak_ke_berapa', 'lintang', 'bujur', 'no_kk',
        'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'jml_saudara_kandung', 'jarak_rumah_ke_sekolah_km',
        'status',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tahun_aktif', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
            if ($tahunAktif) {
                $builder->where('tahun_pelajaran_id', $tahunAktif->id);
            }
        });
    }

    public function tahunPelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
