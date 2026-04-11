<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class KeadaanJasmaniSiswa extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id', 'berat_badan', 'tinggi_badan', 'golongan_darah',
        'nama_riwayat_penyakit', 'kelainan_jasmani'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
