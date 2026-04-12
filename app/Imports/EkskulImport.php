<?php

namespace App\Imports;

use App\Models\BukuInduk;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Models\PrestasiEkstrakurikuler;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EkskulImport implements ToCollection, WithHeadingRow
{
    protected BukuInduk $bukuInduk;
    public int   $successCount = 0;
    public array $errors       = [];

    // Lookup: normalised-header-key → Ekstrakurikuler model
    private array $ekskulBySlug     = [];   // keyed by just the name slug
    private array $ekskulByFullSlug = [];   // keyed by "name_a_b_c_d" (as PhpSpreadsheet emits)

    /** Identity column keys that PhpSpreadsheet emits for our template's Row-2 headers */
    private const IDENTITY_KEYS = [
        'kelas_1_6', 'kelas',
        'semester_1_2', 'semester',
        'tahun_pelajaran_eg_20242025', 'tahun_pelajaran',
    ];

    public function __construct(BukuInduk $bukuInduk)
    {
        $this->bukuInduk = $bukuInduk;

        foreach (Ekstrakurikuler::orderBy('nama_ekstrakurikuler')->get() as $ekskul) {
            // Slug of just the name  →  e.g. "pramuka"
            $nameSlug = $this->slug($ekskul->nama_ekstrakurikuler);
            $this->ekskulBySlug[$nameSlug] = $ekskul;

            // Slug of the full template header "Name (A/B/C/D)"
            // Str::slug converts "(A/B/C/D)" → "abcd", so result is e.g. "pramuka_abcd"
            $fullSlug = $this->slug($ekskul->nama_ekstrakurikuler . ' (A/B/C/D)');
            $this->ekskulByFullSlug[$fullSlug] = $ekskul;
        }
    }

    public function headingRow(): int
    {
        // Template has group labels on Row 1, column headers on Row 2
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $rowIndex => $row) {
            $rowArr = $row->toArray();

            // ── 1. Extract identity columns ──────────────────────────────────
            $kelas    = null;
            $semester = null;

            foreach (['kelas_1_6', 'kelas'] as $k) {
                if (!is_null($rowArr[$k] ?? null)) { $kelas = $rowArr[$k]; break; }
            }
            foreach (['semester_1_2', 'semester'] as $k) {
                if (!is_null($rowArr[$k] ?? null)) { $semester = $rowArr[$k]; break; }
            }

            if (!$kelas || !$semester) {
                $this->errors[] = "Baris " . ($rowIndex + 3) . ": kolom Kelas/Semester kosong — dilewati.";
                continue;
            }

            $kelas    = (int) $kelas;
            $semester = (int) $semester;

            if ($kelas < 1 || $kelas > 12 || !in_array($semester, [1, 2])) {
                $this->errors[] = "Baris " . ($rowIndex + 3) . ": nilai Kelas/Semester tidak valid — dilewati.";
                continue;
            }

            // ── 2. Lookup siswa ──────────────────────────────────────────────
            $siswa = Siswa::withoutGlobalScope('tahun_aktif')
                ->where('nisn', $this->bukuInduk->nisn)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$siswa) {
                $this->errors[] = "Siswa NISN {$this->bukuInduk->nisn} tidak ditemukan.";
                break;
            }

            // ── 3. Collect predikat values from ekskul columns ───────────────
            $toInsert = [];

            foreach ($rowArr as $headerKey => $rawValue) {
                if (in_array($headerKey, self::IDENTITY_KEYS, true)) {
                    continue;
                }

                // Resolve which ekskul this column belongs to:
                // Priority 1 — full slug match ("pramuka_a_b_c_d")
                // Priority 2 — name-only slug match ("pramuka")
                $ekskul = $this->ekskulByFullSlug[$headerKey]
                       ?? $this->ekskulBySlug[$headerKey]
                       ?? null;

                if (!$ekskul) {
                    continue; // Unknown/extra column — ignore
                }

                $predikat = strtoupper(trim((string) ($rawValue ?? '')));

                if (!in_array($predikat, ['A', 'B', 'C', 'D'], true)) {
                    continue; // Blank or invalid — skip (don't overwrite with empty)
                }

                $toInsert[] = [
                    'siswa_id'           => $siswa->id,
                    'ekstrakurikuler_id' => $ekskul->id,
                    'kelas'              => $kelas,
                    'semester'           => $semester,
                    'predikat'           => $predikat,
                ];
            }

            if (empty($toInsert)) {
                continue; // Nothing to save for this row
            }

            // ── 4. Replace existing data for this kelas+semester ─────────────
            PrestasiEkstrakurikuler::where('siswa_id', $siswa->id)
                ->where('kelas', $kelas)
                ->where('semester', $semester)
                ->delete();

            foreach ($toInsert as $data) {
                PrestasiEkstrakurikuler::create($data);
            }

            $this->successCount++;
        }
    }

    /**
     * Produce the same slug that maatwebsite/excel uses for heading normalisation.
     * Maatwebsite uses Str::slug($heading, '_') under the hood.
     */
    private function slug(string $str): string
    {
        return Str::slug(trim($str), '_');
    }
}
