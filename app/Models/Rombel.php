<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'nama',
        'nama_wali_kelas',
        'tingkat',
        'tahun_pelajaran_id',
        'jenis_rombel',
        'kompetensi_keahlian',
        'kurikulum',
        'guru_id',
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function tahunPelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class);
    }
}
