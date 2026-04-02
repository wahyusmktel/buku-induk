<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class SiswaImport implements ToModel, WithStartRow
{
    public $createdCount = 0;
    public $updatedCount = 0;
    public $processedSiswaIds = [];
    protected $matchInCurrentYear = false;

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 7;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip if name is empty (Nama is in Column B, index 1)
        if (empty($row[1]) || $row[1] == 'Nama') {
            return null;
        }

        $tahunAktif = \App\Models\TahunPelajaran::where('is_aktif', true)->first();
        $nisn = $row[4] ?? null;
        $nik = $row[7] ?? null;
        $rombelNama = $row[42] ?? null;

        // Try to handle Rombel synchronization
        $rombelId = null;
        if ($rombelNama && $tahunAktif) {
            $rombel = \App\Models\Rombel::firstOrCreate([
                'nama' => $rombelNama,
                'tahun_pelajaran_id' => $tahunAktif->id
            ]);
            $rombelId = $rombel->id;
        }

        // Try to find existing student in the CURRENT active year first (using Global Scope)
        $existingSiswa = null;
        $this->matchInCurrentYear = false;

        if ($nisn || $nik) {
            $query = Siswa::query(); // Uses active year Global Scope
            if ($nisn && $nik) {
                $query->where(function($q) use ($nisn, $nik) {
                    $q->where('nisn', $nisn)->orWhere('nik', $nik);
                });
            } elseif ($nisn) {
                $query->where('nisn', $nisn);
            } else {
                $query->where('nik', $nik);
            }
            $existingSiswa = $query->first();

            // If found in current year, we will update it
            if ($existingSiswa) {
                $this->matchInCurrentYear = true;
            } else {
                // If not found in current year, search globally across all years to maintain history
                $queryGlobal = Siswa::withoutGlobalScope('tahun_aktif');
                if ($nisn && $nik) {
                    $queryGlobal->where(function($q) use ($nisn, $nik) {
                        $q->where('nisn', $nisn)->orWhere('nik', $nik);
                    });
                } elseif ($nisn) {
                    $queryGlobal->where('nisn', $nisn);
                } else {
                    $queryGlobal->where('nik', $nik);
                }
                $existingSiswa = $queryGlobal->first();
                // If found globally, matchInCurrentYear remains false -> we will create a new record for current year.
            }
        }

        $data = [
            'tahun_pelajaran_id'         => $tahunAktif?->id,
            'rombel_id'                  => $rombelId,
            'nama'                       => $row[1] ?? null,
            'nipd'                       => $row[2] ?? null,
            'jk'                         => $row[3] ?? null,
            'nisn'                       => $nisn,
            'tempat_lahir'               => $row[5] ?? null,
            'tanggal_lahir'              => $this->transformDate($row[6] ?? null),
            'nik'                        => $nik,
            'agama'                      => $row[8] ?? null,
            'alamat'                     => $row[9] ?? null,
            'rt'                         => $row[10] ?? null,
            'rw'                         => $row[11] ?? null,
            'dusun'                      => $row[12] ?? null,
            'kelurahan'                  => $row[13] ?? null,
            'kecamatan'                  => $row[14] ?? null,
            'kode_pos'                   => $row[15] ?? null,
            'jenis_tinggal'              => $row[16] ?? null,
            'alat_transportasi'          => $row[17] ?? null,
            'telepon'                    => $row[18] ?? null,
            'hp'                         => $row[19] ?? null,
            'email'                      => $row[20] ?? null,
            'skhun'                      => $row[21] ?? null,
            'penerima_kps'               => $row[22] ?? null,
            'no_kps'                     => $row[23] ?? null,
            'nama_ayah'                  => $row[24] ?? null,
            'tahun_lahir_ayah'           => is_numeric($row[25] ?? null) ? $row[25] : null,
            'jenjang_pendidikan_ayah'    => $row[26] ?? null,
            'pekerjaan_ayah'             => $row[27] ?? null,
            'penghasilan_ayah'           => $row[28] ?? null,
            'nik_ayah'                   => $row[29] ?? null,
            'nama_ibu'                   => $row[30] ?? null,
            'tahun_lahir_ibu'            => is_numeric($row[31] ?? null) ? $row[31] : null,
            'jenjang_pendidikan_ibu'     => $row[32] ?? null,
            'pekerjaan_ibu'              => $row[33] ?? null,
            'penghasilan_ibu'            => $row[34] ?? null,
            'nik_ibu'                    => $row[35] ?? null,
            'nama_wali'                  => $row[36] ?? null,
            'tahun_lahir_wali'           => is_numeric($row[37] ?? null) ? $row[37] : null,
            'jenjang_pendidikan_wali'    => $row[38] ?? null,
            'pekerjaan_wali'             => $row[39] ?? null,
            'penghasilan_wali'           => $row[40] ?? null,
            'nik_wali'                   => $row[41] ?? null,
            'rombel_saat_ini'            => $rombelNama,
            'no_peserta_un'              => $row[43] ?? null,
            'no_seri_ijazah'             => $row[44] ?? null,
            'penerima_kip'               => $row[45] ?? null,
            'nomor_kip'                  => $row[46] ?? null,
            'nama_di_kip'                => $row[47] ?? null,
            'nomor_kks'                  => $row[48] ?? null,
            'no_registrasi_akta_lahir'   => $row[49] ?? null,
            'bank'                       => $row[50] ?? null,
            'nomor_rekening_bank'        => $row[51] ?? null,
            'rekening_atas_nama'         => $row[52] ?? null,
            'layak_pip'                  => $row[53] ?? null,
            'alasan_layak_pip'           => $row[54] ?? null,
            'kebutuhan_khusus'           => $row[55] ?? null,
            'sekolah_asal'               => $row[56] ?? null,
            'anak_ke_berapa'             => is_numeric($row[57] ?? null) ? $row[57] : null,
            'lintang'                    => $row[58] ?? null,
            'bujur'                      => $row[59] ?? null,
            'no_kk'                      => $row[60] ?? null,
            'berat_badan'                => $row[61] ?? null,
            'tinggi_badan'               => $row[62] ?? null,
            'lingkar_kepala'             => $row[63] ?? null,
            'jml_saudara_kandung'        => is_numeric($row[64] ?? null) ? $row[64] : null,
            'jarak_rumah_ke_sekolah_km'  => $row[65] ?? null,
        ];

        try {
            // Logic: Update if found in current year, otherwise Create new record (even if found globally, to preserve history)
            if ($existingSiswa && $this->matchInCurrentYear) {
                $existingSiswa->update($data);
                $this->updatedCount++;
                $this->processedSiswaIds[] = $existingSiswa->id;
            } else {
                // This covers: 
                // 1. New student entirely
                // 2. Existing student from previous year (new record created for current year)
                $newSiswa = Siswa::create($data);
                $this->createdCount++;
                $this->processedSiswaIds[] = $newSiswa->id;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Row import failed for ' . ($row[1] ?? 'Unknown') . ': ' . $e->getMessage());
            throw $e;
        }

        return null; // Return null because we handled save manually
    }

    /**
     * Transform Excel date to Carbon
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;

        // If it's already a numeric Excel date, transform it
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            } catch (\Throwable $t) {
                // fall through to Carbon parse
            }
        }

        // If it's a string date (e.g. '2018-05-18') or other format
        try {
            return Carbon::parse($value);
        } catch (\Throwable $ce) {
            \Illuminate\Support\Facades\Log::warning('Date parsing failed for: ' . $value);
            return null;
        }
    }

}
