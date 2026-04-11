<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\BukuInduk;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
use App\Models\DataPeriodikSiswa;
use App\Models\KeadaanJasmaniSiswa;
use App\Models\DataOrangTuaSiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterBukuIndukImport implements ToModel, WithStartRow
{
    public $createdCount = 0;
    public $updatedCount = 0;
    
    /**
     * Start from row 2 (assuming header is row 1)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * Mapping column index to model fields (New 35 Columns Format).
    * 0: No, 1: NIS, 2: NISN, 3: NIK, 4: Nama Lengkap, 5: Nama Panggilan, 6: JK, 
    * 7: Tempat Lahir, 8: Tanggal Lahir, 9: Agama, 10: Kewarganegaraan, 11: Jml Sdr Kandung, 12: Jml Sdr Tiri, 
    * 13: Jml Sdr Angkat, 14: Bahasa, 15: Berat, 16: Tinggi, 17: Gol Darah, 18: Penyakit, 19: Kelainan,
    * 20: No Telepon, 21: Alamat, 22: Tinggal Pada, 23: Jarak, 24: Ayah, 25: Pddk Ayah, 26: Pkj Ayah,
    * 27: Ibu, 28: Pddk Ibu, 29: Pkj Ibu, 30: Wali, 31: Hub Wali, 32: Pddk Wali, 33: Pkj Wali, 34: Tingkat
    */
    public function model(array $row)
    {
        // $row[4] adalah Nama Lengkap, jika kosong skip baris ini
        if (empty($row[4])) return null;

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) return null;

        $nis = (string) ($row[1] ?? '');
        $nisn = (string) ($row[2] ?? '');

        DB::transaction(function () use ($row, $tahunAktif, $nisn, $nis) {
            
            $siswaQuery = Siswa::withoutGlobalScope('tahun_aktif')
                ->where('tahun_pelajaran_id', $tahunAktif->id);

            if (!empty($nisn)) {
                $siswaQuery->where('nisn', $nisn);
            } elseif (!empty($nis)) {
                $siswaQuery->where('nipd', $nis); // nipd = nis
            } else {
                $siswaQuery->where('nama', $row[4]);
            }
            
            $siswa = $siswaQuery->first();

            $isNew = false;
            if (!$siswa) {
                $siswa = new Siswa();
                $siswa->tahun_pelajaran_id = $tahunAktif->id;
                $isNew = true;
            }

            $siswa->fill([
                'nipd' => $nis,
                'nisn' => $nisn,
                'nik' => (string) ($row[3] ?? ''),
                'nama' => $row[4],
                'nama_panggilan' => $row[5] ?? null,
                'jk' => $row[6] ?? null,
                'tempat_lahir' => $row[7] ?? null,
                'tanggal_lahir' => $this->transformDate($row[8]),
                'agama' => $row[9] ?? null,
                'kewarganegaraan' => $row[10] ?? null,
                'telepon' => $row[20] ?? null,
                'tingkat_kelas' => $row[34] ?? null,
                'status' => 'Aktif',
            ]);
            $siswa->save();

            if ($isNew) $this->createdCount++; else $this->updatedCount++;

            // 1. Data Periodik
            DataPeriodikSiswa::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'jml_saudara_kandung' => intval($row[11] ?? 0),
                    'jml_saudara_tiri' => intval($row[12] ?? 0),
                    'jml_saudara_angkat' => intval($row[13] ?? 0),
                    'bahasa_sehari_hari' => $row[14] ?? null,
                    'alamat_tinggal' => $row[21] ?? null,
                    'bertempat_tinggal_pada' => $row[22] ?? null,
                    'jarak_tempat_tinggal_ke_sekolah' => $row[23] ?? null,
                ]
            );

            // 2. Keadaan Jasmani
            KeadaanJasmaniSiswa::updateOrCreate(
                ['siswa_id' => $siswa->id],
                [
                    'berat_badan' => floatval($row[15] ?? 0),
                    'tinggi_badan' => floatval($row[16] ?? 0),
                    'golongan_darah' => $row[17] ?? null,
                    'nama_riwayat_penyakit' => $row[18] ?? null,
                    'kelainan_jasmani' => $row[19] ?? null,
                ]
            );

            // 3. Data Orang Tua (Ayah)
            if (!empty($row[24])) {
                DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Ayah'],
                    [
                        'nama' => $row[24],
                        'pendidikan_terakhir' => $row[25] ?? null,
                        'pekerjaan' => $row[26] ?? null,
                    ]
                );
            }

            // Data Orang Tua (Ibu)
            if (!empty($row[27])) {
                DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Ibu'],
                    [
                        'nama' => $row[27],
                        'pendidikan_terakhir' => $row[28] ?? null,
                        'pekerjaan' => $row[29] ?? null,
                    ]
                );
            }

            // Data Wali
            if (!empty($row[30])) {
                DataOrangTuaSiswa::updateOrCreate(
                    ['siswa_id' => $siswa->id, 'jenis' => 'Wali'],
                    [
                        'nama' => $row[30],
                        'status_hubungan_wali' => $row[31] ?? null,
                        'pendidikan_terakhir' => $row[32] ?? null,
                        'pekerjaan' => $row[33] ?? null,
                    ]
                );
            }

            // 4. Update BukuInduk (Fallback existing dependencies)
            if (!empty($nisn)) {
                $bi = BukuInduk::firstOrCreate(['nisn' => $nisn]);
                $bi->no_induk = $nis ?: $bi->no_induk;
                $bi->nama_panggilan = $row[5] ?? $bi->nama_panggilan;
                // Since the others are moved to new tables, we don't have to save ayah, ibu name into this BukuInduk table anymore unless needed.
                $bi->save();
            }
        });

        return null;
    }

    private function transformDate($value)
    {
        if (empty($value)) return null;
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
