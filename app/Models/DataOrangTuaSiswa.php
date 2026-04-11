<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DataOrangTuaSiswa extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id', 'jenis', 'nama', 'pendidikan_terakhir',
        'pekerjaan', 'status_hubungan_wali'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
