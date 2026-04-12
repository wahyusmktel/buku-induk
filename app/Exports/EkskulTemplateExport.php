<?php

namespace App\Exports;

use App\Models\BukuInduk;
use App\Models\Ekstrakurikuler;
use App\Models\TahunPelajaran;
use App\Models\Siswa;
use App\Models\PrestasiEkstrakurikuler;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class EkskulTemplateExport implements FromArray, WithStyles, WithEvents, WithColumnWidths
{
    protected $bukuInduk;

    public function __construct(BukuInduk $bukuInduk)
    {
        $this->bukuInduk = $bukuInduk;
    }

    public function array(): array
    {
        $ekskuls    = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();
        $countEkskul = count($ekskuls);

        // ── Row 1: Category headers ───────────────────────────────────────────
        $row1 = ['', '', ''];   // Kelas, Semester, Tahun (no category label)

        if ($countEkskul > 0) {
            $row1[] = 'DAFTAR EKSTRAKURIKULER';
            for ($i = 0; $i < $countEkskul - 1; $i++) {
                $row1[] = '';
            }
        }

        // ── Row 2: Column headers ─────────────────────────────────────────────
        $row2 = [
            'Kelas (1-6)',
            'Semester (1-2)',
            'Tahun Pelajaran (e.g. 2024/2025)',
        ];

        foreach ($ekskuls as $e) {
            $row2[] = $e->nama_ekstrakurikuler . ' (A/B/C/D)';
        }

        // ── Row 3: Pre-populated with active semester data ────────────────────
        $activeTahunPelajaran = TahunPelajaran::where('is_aktif', true)->first();
        $siswaActive          = Siswa::where('nisn', $this->bukuInduk->nisn)
                                     ->where('tahun_pelajaran_id', $activeTahunPelajaran?->id)
                                     ->first();
        $currentRombel = $siswaActive?->rombel;

        $kelas    = $currentRombel ? $currentRombel->tingkat : '1';
        $smtStr   = strtolower($activeTahunPelajaran?->semester ?? 'ganjil');
        $semester = $smtStr === 'ganjil' ? '1' : '2';
        $tahun    = $activeTahunPelajaran?->tahun ?? '2024/2025';

        // Fetch already-saved ekskul values for active semester
        $savedEkskuls = collect();
        if ($siswaActive) {
            $savedEkskuls = PrestasiEkstrakurikuler::where('siswa_id', $siswaActive->id)
                ->where('kelas', $kelas)
                ->where('semester', $semester)
                ->get()
                ->keyBy('ekstrakurikuler_id');
        }

        $row3 = [$kelas, $semester, $tahun];
        foreach ($ekskuls as $e) {
            $row3[] = $savedEkskuls[$e->id]?->predikat ?? '';
        }

        return [$row1, $row2, $row3];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14,
            'B' => 14,
            'C' => 28,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $ekskuls     = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();
        $countEkskul = count($ekskuls);
        $endEkskul   = 3 + $countEkskul;
        $colEndEkskul = Coordinate::stringFromColumnIndex($endEkskul);

        $styles = [
            // Identitas (Col A-C) — Light Slate (Row 1 & 2)
            'A1:C2' => [
                'font'      => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                ],
            ],

            // Data row (Row 3)
            'A3:' . $colEndEkskul . '3' => [
                'font'      => ['bold' => true, 'color' => ['rgb' => '374151']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FAFAFA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
                ],
            ],
        ];

        // Ekskul columns — Soft Violet (Row 1 & 2)
        if ($countEkskul > 0) {
            $styles['D1:' . $colEndEkskul . '2'] = [
                'font'      => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EDE9FE']],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDD6FE']],
                ],
            ];
        }

        return $styles;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet       = $event->sheet->getDelegate();
                $ekskuls     = Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get();
                $countEkskul = count($ekskuls);

                // Set default row heights
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(36);

                // Set ekskul column widths dynamically (auto-size approximation)
                for ($i = 0; $i < $countEkskul; $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex(4 + $i);
                    $sheet->getColumnDimension($colLetter)->setWidth(22);
                }

                if ($countEkskul > 0) {
                    // Merge "DAFTAR EKSTRAKURIKULER" across all ekskul columns
                    $startColLetter = Coordinate::stringFromColumnIndex(4);
                    $endColLetter   = Coordinate::stringFromColumnIndex(3 + $countEkskul);
                    $sheet->mergeCells("{$startColLetter}1:{$endColLetter}1");
                }

                // Merge the 3 identity cells in row 1 (keep them blank/empty grouped)
                // Add data-validation dropdown for predikat cells (A/B/C/D) on row 3+
                $validation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
                $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
                $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(true);
                $validation->setShowDropDown(false);
                $validation->setFormula1('"A,B,C,D"');

                for ($i = 0; $i < $countEkskul; $i++) {
                    $colLetter = Coordinate::stringFromColumnIndex(4 + $i);
                    // Apply dropdown to rows 3 onwards (up to row 52 to cover future imports)
                    for ($row = 3; $row <= 52; $row++) {
                        $cell = $sheet->getCell("{$colLetter}{$row}");
                        $cell->setDataValidation(clone $validation);
                    }
                }

                // Freeze panes at D3 so identity columns stay visible when scrolling
                $sheet->freezePane('D3');

                // Add a helper note in row 2 col A
                $sheet->getComment('A2')->getText()->createTextRun(
                    'Isi dengan angka kelas, misal: 1, 2, 3 dst.'
                );
            },
        ];
    }
}
