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
        'nilai_agama', 'nilai_pkn', 'nilai_bindo', 'nilai_mtk',
        'nilai_ipa', 'nilai_ips', 'nilai_sbk', 'nilai_pjok',
        'nilai_mulok', 'nilai_mulok2',
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

    /**
     * Get the list of subject score fields.
     */
    public static function subjectFields(): array
    {
        return [
            'nilai_agama'  => 'Agama',
            'nilai_pkn'    => 'PKn',
            'nilai_bindo'  => 'Bahasa Indonesia',
            'nilai_mtk'    => 'Matematika',
            'nilai_ipa'    => 'IPA',
            'nilai_ips'    => 'IPS',
            'nilai_sbk'    => 'SBK',
            'nilai_pjok'   => 'PJOK',
            'nilai_mulok'  => 'Muatan Lokal',
            'nilai_mulok2' => 'Muatan Lokal 2',
        ];
    }

    /**
     * Auto-compute jumlah and rata_rata before saving.
     */
    protected static function booted()
    {
        static::saving(function (self $model) {
            $subjects = array_keys(self::subjectFields());
            $values = collect($subjects)->map(fn($f) => $model->$f)->filter(fn($v) => $v !== null);
            if ($values->count() > 0) {
                $model->jumlah_nilai = $values->sum();
                $model->rata_rata = round($values->avg(), 2);
            }
        });
    }
}
