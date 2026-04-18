<?php

namespace App\Exports;

use App\Models\Rombel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected Rombel $rombel;

    public function __construct(Rombel $rombel)
    {
        $this->rombel = $rombel;
    }

    public function title(): string
    {
        return 'Template Absensi - ' . $this->rombel->nama;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN',
            'NIS/NIPD',
            'Hadir',
            'Sakit',
            'Izin',
            'Alpha',
        ];
    }

    public function array(): array
    {
        $rows = [];
        $no   = 1;

        foreach ($this->rombel->siswas as $siswa) {
            $rows[] = [
                $no++,
                $siswa->nama,
                $siswa->nisn  ?? '',
                $siswa->nipd  ?? '',
                '',  // Hadir
                '',  // Sakit
                '',  // Izin
                '',  // Alpha
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        // Info baris judul di atas heading
        $rombel      = $this->rombel;
        $tahun       = $rombel->tahunPelajaran?->tahun ?? '';
        $semester    = $rombel->tahunPelajaran?->semester ?? '';
        $namaWali    = $rombel->nama_wali_kelas ?? '-';

        // Insert 3 info rows before heading (the package prepends headings at row 1,
        // so we add metadata as the first rows via the array, and offset heading row)
        // Actually, WithHeadings always goes to row 1. We use styles to add info rows
        // by inserting blank rows via sheet manipulation after render.
        // Keep it simple: just style heading row and data rows.

        return [
            // Heading row (row 1)
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
            // All data columns
            'A:H' => [
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
