<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PrestasiEkstrakurikuler extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'kelas',
        'semester',
        'predikat',
        'keterangan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }
}
