<?php

namespace App\Exports;

use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class MataPelajaranTemplateExport implements FromArray, WithStyles, WithEvents, WithColumnWidths
{
    public function array(): array
    {
        $header = ['Nama Mata Pelajaran', 'Kelompok / Kategori', 'Urutan Tampil'];

        $samples = [
            ['Pendidikan Agama Islam', 'Muatan Nasional', 1],
            ['Pendidikan Pancasila', 'Muatan Nasional', 2],
            ['Bahasa Indonesia', 'Muatan Nasional', 3],
            ['Matematika', 'Muatan Nasional', 4],
            ['Ilmu Pengetahuan Alam dan Sosial', 'Muatan Nasional', 5],
            ['Seni Budaya', 'Muatan Kewilayahan', 6],
            ['Pendidikan Jasmani, Olahraga dan Kesehatan', 'Muatan Kewilayahan', 7],
            ['Bahasa Inggris', 'Muatan Lokal', 8],
            ['Bahasa Daerah', 'Muatan Lokal', 9],
        ];

        $rows = [$header];
        foreach ($samples as $sample) {
            $rows[] = $sample;
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 45,
            'B' => 30,
            'C' => 16,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0369A1'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '0284C7']],
                ],
            ],
            'A2:C10' => [
                'font'      => ['size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getRowDimension(1)->setRowHeight(22);

                // Zebra striping on sample rows
                for ($i = 2; $i <= 10; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle("A{$i}:C{$i}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F0F9FF');
                    }
                }

                // Note row
                $noteRow = 12;
                $sheet->setCellValue("A{$noteRow}", '📌 CATATAN:');
                $sheet->setCellValue("A" . ($noteRow + 1), '• Kolom "Nama Mata Pelajaran" dan "Kelompok / Kategori" wajib diisi.');
                $sheet->setCellValue("A" . ($noteRow + 2), '• Kolom "Urutan Tampil" opsional. Jika kosong, urutan otomatis ditambahkan di akhir.');
                $sheet->setCellValue("A" . ($noteRow + 3), '• Nama yang sudah ada di database akan dilewati (tidak digandakan).');
                $sheet->setCellValue("A" . ($noteRow + 4), '• Hapus baris contoh ini sebelum mengisi data sebenarnya, atau tambahkan data di bawahnya.');

                $sheet->getStyle("A{$noteRow}:C" . ($noteRow + 4))->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '475569']],
                    'alignment' => ['wrapText' => true],
                ]);
                $sheet->getStyle("A{$noteRow}")->getFont()->setBold(true)->getColor()->setRGB('0369A1');
                $sheet->getRowDimension($noteRow + 1)->setRowHeight(14);
            },
        ];
    }
}
