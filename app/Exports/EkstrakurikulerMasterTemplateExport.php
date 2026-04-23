<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class EkstrakurikulerMasterTemplateExport implements FromArray, WithStyles, WithEvents, WithColumnWidths
{
    public function array(): array
    {
        $header = ['Nama Ekstrakurikuler', 'Keterangan / Deskripsi'];

        $samples = [
            ['Pramuka', 'Kegiatan kepanduan wajib bagi seluruh siswa'],
            ['Sepak Bola', 'Olahraga tim yang diselenggarakan sore hari'],
            ['Bola Voli', 'Olahraga tim putra dan putri'],
            ['Seni Tari', 'Pengembangan bakat seni budaya daerah'],
            ['Seni Musik', 'Latihan alat musik dan vokal'],
            ['Tahfidz Quran', 'Program hafalan Al-Quran'],
            ['Drumband', 'Kelompok marching band sekolah'],
            ['Komputer / TIK', 'Pengenalan teknologi informasi dan komunikasi'],
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
            'A' => 35,
            'B' => 50,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4338CA'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4F46E5']],
                ],
            ],
            'A2:B9' => [
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

                // Zebra striping
                for ($i = 2; $i <= 9; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle("A{$i}:B{$i}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('EEF2FF');
                    }
                }

                // Note row
                $noteRow = 11;
                $sheet->setCellValue("A{$noteRow}", '📌 CATATAN:');
                $sheet->setCellValue("A" . ($noteRow + 1), '• Kolom "Nama Ekstrakurikuler" wajib diisi, "Keterangan / Deskripsi" opsional.');
                $sheet->setCellValue("A" . ($noteRow + 2), '• Nama yang sudah ada di database akan dilewati (tidak digandakan).');
                $sheet->setCellValue("A" . ($noteRow + 3), '• Hapus baris contoh ini sebelum mengisi data sebenarnya, atau tambahkan data di bawahnya.');

                $sheet->getStyle("A{$noteRow}:B" . ($noteRow + 3))->applyFromArray([
                    'font'      => ['size' => 9, 'color' => ['rgb' => '475569']],
                    'alignment' => ['wrapText' => true],
                ]);
                $sheet->getStyle("A{$noteRow}")->getFont()->setBold(true)->getColor()->setRGB('4338CA');
            },
        ];
    }
}
