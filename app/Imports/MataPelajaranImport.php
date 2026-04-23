<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class MataPelajaranImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $successCount = 0;
    public int $skipCount    = 0;
    public array $errors     = [];

    public function model(array $row): ?MataPelajaran
    {
        $nama     = trim($row['nama_mata_pelajaran'] ?? $row['nama'] ?? '');
        $kelompok = trim($row['kelompok_kategori']   ?? $row['kelompok'] ?? '');
        $urutan   = $row['urutan_tampil']             ?? $row['urutan']  ?? null;

        if (empty($nama) || empty($kelompok)) {
            $this->skipCount++;
            return null;
        }

        if (MataPelajaran::where('nama', $nama)->exists()) {
            $this->skipCount++;
            $this->errors[] = "Baris dilewati — nama sudah ada: \"{$nama}\"";
            return null;
        }

        $maxUrutan = MataPelajaran::max('urutan') ?? 0;

        $this->successCount++;

        return new MataPelajaran([
            'nama'     => $nama,
            'kelompok' => $kelompok,
            'urutan'   => is_numeric($urutan) ? (int) $urutan : ($maxUrutan + $this->successCount),
            'is_aktif' => true,
        ]);
    }
}
