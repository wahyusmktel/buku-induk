<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\BukuInduk;
use App\Models\Rombel;
use App\Models\TahunPelajaran;
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
    * Mapping column index to model fields.
    * 0: Nama, 1: NISN, 2: NIK, 3: JK, 4: Tempat Lahir, 5: Tanggal Lahir, 6: Angkatan (Tahun Masuk), 
    * 7: Rombel, 8: Agama, 9: Alamat, 10: No Induk, 11: Nama Panggilan, 12: Nama Ayah, 13: Nama Ibu
    */
    public function model(array $row)
    {
        if (empty($row[0])) return null;

        $tahunAktif = TahunPelajaran::where('is_aktif', true)->first();
        if (!$tahunAktif) return null;

        $nisn = (string) ($row[1] ?? '');
        $rombelNama = $row[7] ?? null;

        DB::transaction(function () use ($row, $tahunAktif, $nisn, $rombelNama) {
            
            // 1. Manage Rombel
            $rombelId = null;
            if ($rombelNama) {
                $rombel = Rombel::firstOrCreate(
                    ['nama' => $rombelNama, 'tahun_pelajaran_id' => $tahunAktif->id],
                    ['tingkat' => $row[14] ?? null]
                );
                
                // Ensure Rombel has Tingkat if it was just changed in Excel
                if (!empty($row[14]) && $rombel->tingkat != $row[14]) {
                    $rombel->update(['tingkat' => $row[14]]);
                }

                $rombelId = $rombel->id;
            }

            // 2. Find or Create Siswa
            $siswa = Siswa::withoutGlobalScope('tahun_aktif')
                ->where('nisn', $nisn)
                ->where('tahun_pelajaran_id', $tahunAktif->id)
                ->first();

            $isNew = false;
            if (!$siswa) {
                $siswa = new Siswa();
                $siswa->tahun_pelajaran_id = $tahunAktif->id;
                $isNew = true;
            }

            $siswa->fill([
                'nama' => $row[0],
                'nisn' => $nisn,
                'nik' => (string) ($row[2] ?? ''),
                'jk' => $row[3],
                'tempat_lahir' => $row[4],
                'tanggal_lahir' => $this->transformDate($row[5]),
                'tahun_masuk' => $row[6],
                'rombel_id' => $rombelId,
                'rombel_saat_ini' => $rombelNama,
                'tingkat_kelas' => $row[14] ?? null,
                'agama' => $row[8],
                'alamat' => $row[9],
                'nama_ayah' => $row[12],
                'nama_ibu' => $row[13],
                'status' => 'Aktif',
            ]);
            $siswa->save();

            if ($isNew) $this->createdCount++; else $this->updatedCount++;

            // 3. Find or Create BukuInduk
            $bi = BukuInduk::firstOrCreate(['nisn' => $nisn]);
            $bi->update([
                'no_induk' => $row[10] ?? $bi->no_induk,
                'nama_panggilan' => $row[11] ?? $bi->nama_panggilan,
                'nama_ayah' => $row[12] ?? $bi->nama_ayah,
                'nama_ibu' => $row[13] ?? $bi->nama_ibu,
                // Add more fields here if the client provides a more detailed template
            ]);
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
