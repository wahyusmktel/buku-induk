<?php

namespace App\Imports;

use App\Models\Ekstrakurikuler;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class EkstrakurikulerMasterImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $successCount = 0;
    public int $skipCount    = 0;
    public array $errors     = [];

    public function model(array $row): ?Ekstrakurikuler
    {
        $nama      = trim($row['nama_ekstrakurikuler'] ?? $row['nama'] ?? '');
        $deskripsi = trim($row['keterangan_deskripsi'] ?? $row['deskripsi'] ?? '');

        if (empty($nama)) {
            $this->skipCount++;
            return null;
        }

        if (Ekstrakurikuler::where('nama_ekstrakurikuler', $nama)->exists()) {
            $this->skipCount++;
            $this->errors[] = "Baris dilewati — nama sudah ada: \"{$nama}\"";
            return null;
        }

        $this->successCount++;

        return new Ekstrakurikuler([
            'nama_ekstrakurikuler' => $nama,
            'deskripsi'            => $deskripsi ?: null,
            'is_aktif'             => true,
        ]);
    }
}
