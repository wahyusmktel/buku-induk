<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class SiswaImport implements ToModel, WithStartRow
{
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
        // Skip if name is empty
        if (empty($row[0])) {
            return null;
        }

        return new Siswa([
            'nama'                       => $row[0],
            'nipd'                       => $row[1],
            'jk'                         => $row[2],
            'nisn'                       => $row[3],
            'tempat_lahir'               => $row[4],
            'tanggal_lahir'              => $this->transformDate($row[5]),
            'nik'                        => $row[6],
            'agama'                      => $row[7],
            'alamat'                     => $row[8],
            'rt'                         => $row[9],
            'rw'                         => $row[10],
            'dusun'                      => $row[11],
            'kelurahan'                  => $row[12],
            'kecamatan'                  => $row[13],
            'kode_pos'                   => $row[14],
            'jenis_tinggal'              => $row[15],
            'alat_transportasi'          => $row[16],
            'telepon'                    => $row[17],
            'hp'                         => $row[18],
            'email'                      => $row[19],
            'skhun'                      => $row[20],
            'penerima_kps'               => $row[21],
            'no_kps'                     => $row[22],
            'nama_ayah'                  => $row[23],
            'tahun_lahir_ayah'           => $row[24],
            'jenjang_pendidikan_ayah'    => $row[25],
            'pekerjaan_ayah'             => $row[26],
            'penghasilan_ayah'           => $row[27],
            'nik_ayah'                   => $row[28],
            'nama_ibu'                   => $row[29],
            'tahun_lahir_ibu'            => $row[30],
            'jenjang_pendidikan_ibu'     => $row[31],
            'pekerjaan_ibu'              => $row[32],
            'penghasilan_ibu'            => $row[33],
            'nik_ibu'                    => $row[34],
            'nama_wali'                  => $row[35],
            'tahun_lahir_wali'           => $row[36],
            'jenjang_pendidikan_wali'    => $row[37],
            'pekerjaan_wali'             => $row[38],
            'penghasilan_wali'           => $row[39],
            'nik_wali'                   => $row[40],
            'rombel_saat_ini'            => $row[41],
            'no_peserta_un'              => $row[42],
            'no_seri_ijazah'             => $row[43],
            'penerima_kip'               => $row[44],
            'nomor_kip'                  => $row[45],
            'nama_di_kip'                => $row[46],
            'nomor_kks'                  => $row[47],
            'no_registrasi_akta_lahir'   => $row[48],
            'bank'                       => $row[49],
            'nomor_rekening_bank'        => $row[50],
            'rekening_atas_nama'         => $row[51],
            'layak_pip'                  => $row[52],
            'alasan_layak_pip'           => $row[53],
            'kebutuhan_khusus'           => $row[54],
            'sekolah_asal'               => $row[55],
            'anak_ke_berapa'             => $row[56],
            'lintang'                    => $row[57],
            'bujur'                      => $row[58],
            'no_kk'                      => $row[59],
            'berat_badan'                => $row[60],
            'tinggi_badan'               => $row[61],
            'lingkar_kepala'             => $row[62],
            'jml_saudara_kandung'        => $row[63],
            'jarak_rumah_ke_sekolah_km'  => $row[64],
        ]);
    }

    /**
     * Transform Excel date to Carbon
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;

        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        } catch (\ErrorException $e) {
            return Carbon::parse($value);
        }
    }
}
