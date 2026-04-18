<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AlumniExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected Collection $alumni;
    protected int $rowNumber = 0;

    public function __construct(Collection $alumni)
    {
        $this->alumni = $alumni;
    }

    public function collection(): Collection
    {
        return $this->alumni;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NISN',
            'NIS/NIPD',
            'Jenis Kelamin',
            'Tahun Pelajaran',
            'Semester',
            'Status',
            'Kelas Terakhir',
        ];
    }

    public function map($siswa): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $siswa->nama,
            $siswa->nisn,
            $siswa->nipd,
            $siswa->jk === 'L' ? 'Laki-laki' : 'Perempuan',
            $siswa->tahunPelajaran?->tahun ?? '-',
            $siswa->tahunPelajaran?->semester ?? '-',
            $siswa->status,
            $siswa->rombel_saat_ini,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4F46E5'], // indigo-600
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A:I' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
