<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PrestasiNilai extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'prestasi_nilais';

    protected $fillable = [
        'prestasi_belajar_id', 'mata_pelajaran_id', 'nilai'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function prestasiBelajar()
    {
        return $this->belongsTo(PrestasiBelajar::class);
    }
}
