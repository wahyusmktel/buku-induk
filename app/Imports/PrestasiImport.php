<?php

namespace App\Imports;

use App\Models\BukuInduk;
use App\Models\MataPelajaran;
use App\Models\PrestasiBelajar;
use App\Models\PrestasiNilai;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PrestasiImport implements ToModel, WithHeadingRow
{
    protected $bukuInduk;
    protected $mapels;
    public $successCount = 0;

    public function __construct(BukuInduk $bukuInduk)
    {
        $this->bukuInduk = $bukuInduk;
        $this->mapels = MataPelajaran::all();
    }

    public function model(array $row)
    {
        // Simple validation to ensure we have required fields
        $kelas = $row['kelas_1_6'] ?? $row['kelas'] ?? null;
        $semester = $row['semester_1_2'] ?? $row['semester'] ?? null;
        $tahun = $row['tahun_pelajaran_eg_20242025'] ?? $row['tahun_pelajaran'] ?? null;

        if (!$kelas || !$semester || !$tahun) {
            return null;
        }

        // Data for PrestasiBelajar
        $prestasiData = [
            'buku_induk_id' => $this->bukuInduk->id,
            'kelas' => $kelas,
            'semester' => $semester,
            'tahun_pelajaran' => $tahun,
            'peringkat' => $row['peringkat'] ?? null,
            'hadir_sakit' => $row['sakit_hari'] ?? 0,
            'hadir_izin' => $row['izin_hari'] ?? 0,
            'hadir_alpha' => $row['alpha_hari'] ?? 0,
            'sikap' => $row['sikap_baikcukupkurang'] ?? $row['sikap'] ?? null,
            'kerajinan' => $row['kerajinan_baikcukupkurang'] ?? $row['kerajinan'] ?? null,
            'kebersihan_kerapian' => $row['kebersihan_baikcukupkurang'] ?? $row['kebersihan'] ?? null,
            'keterangan_kenaikan' => $row['kenaikan_naiktidak_naik'] ?? $row['keterangan_kenaikan'] ?? null,
            'tgl_keputusan_kenaikan' => $this->transformDate($row['tgl_keputusan_yyyymmdd'] ?? $row['tgl_keputusan'] ?? null),
        ];

        $prestasi = PrestasiBelajar::updateOrCreate(
            [
                'buku_induk_id' => $this->bukuInduk->id,
                'kelas'         => $kelas,
                'semester'      => $semester,
            ],
            $prestasiData
        );

        // Map Nilai
        foreach ($this->mapels as $mapel) {
            $slug = \Illuminate\Support\Str::slug($mapel->nama, '_');
            // Try to find the value using various possible header variations
            $nilai = $row[$slug] ?? null;

            if ($nilai !== null && $nilai !== '') {
                PrestasiNilai::updateOrCreate(
                    [
                        'prestasi_belajar_id' => $prestasi->id,
                        'mata_pelajaran_id' => $mapel->id,
                    ],
                    ['nilai' => $nilai]
                );
            }
        }

        $prestasi->recalculateTotals();
        $this->successCount++;

        return null; // Return null because we handled update manually
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;

        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            } catch (\Throwable $t) {}
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
