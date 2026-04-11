<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DataPeriodikSiswa extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id', 'jml_saudara_kandung', 'jml_saudara_tiri', 'jml_saudara_angkat',
        'bahasa_sehari_hari', 'alamat_tinggal', 'bertempat_tinggal_pada', 'jarak_tempat_tinggal_ke_sekolah'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
