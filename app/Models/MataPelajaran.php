<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MataPelajaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'mata_pelajarans';

    protected $fillable = [
        'nama', 'kelompok', 'urutan', 'is_aktif'
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan' => 'integer'
    ];
}
