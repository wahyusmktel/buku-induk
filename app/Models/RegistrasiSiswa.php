<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RegistrasiSiswa extends Model
{
    use HasUuids;

    protected $fillable = [
        'siswa_id', 'jenis_registrasi', 'tanggal', 'tujuan_sekolah',
        'tujuan_kelas', 'alasan_catatan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
