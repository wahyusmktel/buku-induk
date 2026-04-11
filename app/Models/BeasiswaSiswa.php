<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BeasiswaSiswa extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id', 'jenis_beasiswa', 'keterangan', 'tahun'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
