<?php

namespace App\Exports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class PrestasiTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Return 1 empty row for example
        $mapels = MataPelajaran::orderBy('nama')->get();
        $row = [
            '1', // Kelas
            '1', // Semester
            '2024/2025', // Tahun Pelajaran
        ];

        foreach ($mapels as $m) {
            $row[] = '85'; // Placeholder nilai
        }

        $row = array_merge($row, [
            '1', // Peringkat
            '0', // Sakit
            '0', // Izin
            '0', // Alpha
            'Baik', // Sikap
            'Baik', // Kerajinan
            'Baik', // Kebersihan
            'Naik', // Kenaikan
            date('Y-m-d'), // Tgl Keputusan
        ]);

        return new Collection([$row]);
    }

    public function headings(): array
    {
        $mapels = MataPelajaran::orderBy('nama')->pluck('nama')->toArray();
        
        $headers = [
            'Kelas (1-6)',
            'Semester (1-2)',
            'Tahun Pelajaran (e.g. 2024/2025)',
        ];

        foreach ($mapels as $nama) {
            $headers[] = $nama;
        }

        return array_merge($headers, [
            'Peringkat',
            'Sakit (hari)',
            'Izin (hari)',
            'Alpha (hari)',
            'Sikap (Baik/Cukup/Kurang)',
            'Kerajinan (Baik/Cukup/Kurang)',
            'Kebersihan (Baik/Cukup/Kurang)',
            'Kenaikan (Naik/Tidak Naik)',
            'Tgl Keputusan (YYYY-MM-DD)',
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold and blue background
            1    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
