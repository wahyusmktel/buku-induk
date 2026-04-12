<?php

namespace App\Exports;

use App\Models\BukuInduk;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PrestasiTemplateExport implements FromArray, WithStyles, WithEvents
{
    protected $bukuInduk;

    public function __construct(BukuInduk $bukuInduk)
    {
        $this->bukuInduk = $bukuInduk;
    }

    public function array(): array
    {
        $mapels = MataPelajaran::orderBy('urutan')->get();
        $countMapel = count($mapels);

        // Row 1: CATEGORIES
        $row1 = [
            '', '', '', // For Kelas, Semester, Tahun
        ];
        
        // Pad for Mapel category
        if ($countMapel > 0) {
            $row1[] = 'DAFTAR MATA PELAJARAN';
            for ($i = 0; $i < $countMapel - 1; $i++) {
                $row1[] = ''; // empty space for merged cells effect
            }
        }

        // Ketidakhadiran
        $row1[] = 'KETIDAKHADIRAN';
        $row1[] = '';
        $row1[] = '';

        // Kepribadian
        $row1[] = 'KEPRIBADIAN';
        $row1[] = '';
        $row1[] = '';

        // Peringkat & Kenaikan
        $row1[] = 'PERINGKAT & KENAIKAN KELAS';
        $row1[] = '';

        // Row 2: COLUMNS
        $row2 = [
            'Kelas (1-6)',
            'Semester (1-2)',
            'Tahun Pelajaran (e.g. 2024/2025)',
        ];

        foreach ($mapels as $m) {
            $row2[] = $m->nama;
        }

        $row2 = array_merge($row2, [
            'Sakit (hari)',
            'Izin (hari)',
            'Alpha (hari)',
            'Sikap (A/B/C/D)',
            'Kerajinan (A/B/C/D)',
            'Kebersihan (A/B/C/D)',
            'Peringkat',
            'Kenaikan (Naik/Tidak Naik)',
        ]);

        $activeTahunPelajaran = TahunPelajaran::where('is_aktif', true)->first();
        $siswaActive = \App\Models\Siswa::where('nisn', $this->bukuInduk->nisn)->where('tahun_pelajaran_id', $activeTahunPelajaran?->id)->first();
        $currentRombel = $siswaActive ? $siswaActive->rombel : null;

        $kelas = $currentRombel ? $currentRombel->tingkat : '1';
        $semesterString = strtolower($activeTahunPelajaran?->semester ?? 'ganjil');
        $semester = $semesterString == 'ganjil' ? '1' : '2';
        $tahunString = $activeTahunPelajaran?->tahun ?? '2024/2025';

        $activePrestasi = null;
        if ($currentRombel && $activeTahunPelajaran) {
            $activePrestasi = $this->bukuInduk->prestasis()
                ->where('kelas', $kelas)
                ->where('semester', $semester)
                ->first();
        }

        $row3 = [
            $kelas,
            $semester,
            $tahunString,
        ];

        foreach ($mapels as $m) {
            if ($activePrestasi) {
                $nilai_val = $activePrestasi->nilais->where('mata_pelajaran_id', $m->id)->first()?->nilai;
                $row3[] = $nilai_val !== null ? $nilai_val : '';
            } else {
                $row3[] = ''; 
            }
        }

        $row3 = array_merge($row3, [
            $activePrestasi?->hadir_sakit ?? '0',
            $activePrestasi?->hadir_izin ?? '0',
            $activePrestasi?->hadir_alpha ?? '0',
            $activePrestasi?->sikap ?? '',
            $activePrestasi?->kerajinan ?? '',
            $activePrestasi?->kebersihan_kerapian ?? '',
            $activePrestasi?->peringkat ?? '',
            $activePrestasi?->keterangan_kenaikan ?? '',
        ]);

        return [$row1, $row2, $row3];
    }

    public function styles(Worksheet $sheet)
    {
        $countMapel = MataPelajaran::count();
        $endMapel = 3 + $countMapel;
        $startAbsen = $endMapel + 1;
        $endAbsen = $endMapel + 3;
        $startPribadi = $endAbsen + 1;
        $endPribadi = $endAbsen + 3;
        $startRank = $endPribadi + 1;
        $endRank = $endPribadi + 2;

        $colEndMapel = Coordinate::stringFromColumnIndex($endMapel);
        $colStartAbsen = Coordinate::stringFromColumnIndex($startAbsen);
        $colEndAbsen = Coordinate::stringFromColumnIndex($endAbsen);
        $colStartPribadi = Coordinate::stringFromColumnIndex($startPribadi);
        $colEndPribadi = Coordinate::stringFromColumnIndex($endPribadi);
        $colStartRank = Coordinate::stringFromColumnIndex($startRank);
        $colEndRank = Coordinate::stringFromColumnIndex($endRank);

        $styles = [
            // Identitas (Col A-C) - Light Blue/Slate
            'A1:C2' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F1F5F9'] // Slate 100
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                ],
            ],
            // Ketidakhadiran - Light Yellow
            "{$colStartAbsen}1:{$colEndAbsen}2" => [
                'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FEF9C3'] // Yellow 100
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FDE047']],
                ],
            ],
            // Kepribadian - Light Purple
            "{$colStartPribadi}1:{$colEndPribadi}2" => [
                'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3E8FF'] // Purple 100
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E9D5FF']],
                ],
            ],
            // Peringkat & Kenaikan - Light Orange
            "{$colStartRank}1:{$colEndRank}2" => [
                'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFEDD5'] // Orange 100
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FED7AA']],
                ],
            ],
        ];

        // Mapel (Only if exist) - Light Green
        if ($countMapel > 0) {
            $styles["D1:{$colEndMapel}2"] = [
                'font' => ['bold' => true, 'color' => ['rgb' => '1E293B']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DCFCE7'] // Green 100
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BBF7D0']],
                ],
            ];
        }

        return $styles;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $countMapel = MataPelajaran::count();
                
                if ($countMapel > 0) {
                    $startMapel = 4;
                    $endMapel = 3 + $countMapel;
                    
                    // Merge DAFTAR MATA PELAJARAN
                    $startColMapel = Coordinate::stringFromColumnIndex($startMapel);
                    $endColMapel = Coordinate::stringFromColumnIndex($endMapel);
                    $sheet->mergeCells("{$startColMapel}1:{$endColMapel}1");

                    // Merge KETIDAKHADIRAN
                    $startAbsen = $endMapel + 1;
                    $endAbsen = $endMapel + 3;
                    $startColAbsen = Coordinate::stringFromColumnIndex($startAbsen);
                    $endColAbsen = Coordinate::stringFromColumnIndex($endAbsen);
                    $sheet->mergeCells("{$startColAbsen}1:{$endColAbsen}1");

                    // Merge KEPRIBADIAN
                    $startPribadi = $endAbsen + 1;
                    $endPribadi = $endAbsen + 3;
                    $startColPribadi = Coordinate::stringFromColumnIndex($startPribadi);
                    $endColPribadi = Coordinate::stringFromColumnIndex($endPribadi);
                    $sheet->mergeCells("{$startColPribadi}1:{$endColPribadi}1");

                    // Merge PERINGKAT
                    $startRank = $endPribadi + 1;
                    $endRank = $endPribadi + 2;
                    $startColRank = Coordinate::stringFromColumnIndex($startRank);
                    $endColRank = Coordinate::stringFromColumnIndex($endRank);
                    $sheet->mergeCells("{$startColRank}1:{$endColRank}1");
                }
            },
        ];
    }
}
