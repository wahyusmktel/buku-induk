<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrestasiBelajar extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'prestasi_belajars';

    protected $fillable = [
        'buku_induk_id', 'kelas', 'semester', 'tahun_pelajaran',
        'jumlah_nilai', 'rata_rata', 'peringkat',
        'sikap', 'kerajinan', 'kebersihan_kerapian',
        'hadir_sakit', 'hadir_izin', 'hadir_alpha',
        'keterangan_kenaikan', 'tgl_keputusan_kenaikan', 'catatan_guru',
    ];

    protected $casts = [
        'tgl_keputusan_kenaikan' => 'date',
        'kelas' => 'integer',
        'semester' => 'integer',
        'peringkat' => 'integer',
    ];

    public function bukuInduk()
    {
        return $this->belongsTo(BukuInduk::class);
    }

    public function nilais()
    {
        return $this->hasMany(PrestasiNilai::class, 'prestasi_belajar_id');
    }

    /**
     * Hitung ulang total dan rata-rata
     */
    public function recalculateTotals()
    {
        $sum = $this->nilais()->sum('nilai');
        $count = $this->nilais()->whereNotNull('nilai')->count();
        
        $this->update([
            'jumlah_nilai' => $sum,
            'rata_rata' => $count > 0 ? round($sum / $count, 2) : 0,
        ]);
    }

}
