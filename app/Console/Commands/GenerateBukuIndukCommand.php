<?php

namespace App\Console\Commands;

use App\Models\BukuInduk;
use App\Models\Siswa;
use Illuminate\Console\Command;

class GenerateBukuIndukCommand extends Command
{
    protected $signature = 'buku-induk:generate
                            {--dry-run : Tampilkan preview tanpa menyimpan data}
                            {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Generate catatan BukuInduk untuk siswa existing yang belum memilikinya';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════════╗');
        $this->info('║   Generate Buku Induk — Buku Induk Digital   ║');
        $this->info('╚══════════════════════════════════════════════╝');
        $this->info('');

        $isDryRun = $this->option('dry-run');

        // Ambil semua siswa yang punya NISN tapi belum ada BukuInduk-nya
        $existingNisns = BukuInduk::pluck('nisn')->filter()->toArray();

        $siswas = Siswa::withoutGlobalScope('tahun_aktif')
            ->whereNotNull('nisn')
            ->whereNotIn('nisn', $existingNisns)
            ->orderBy('nama')
            ->get(['id', 'nisn', 'nama', 'nipd', 'nama_ayah', 'nama_ibu']);

        $total = $siswas->count();

        if ($total === 0) {
            $this->info('✅ Semua siswa yang memiliki NISN sudah memiliki catatan Buku Induk.');
            return self::SUCCESS;
        }

        $this->warn("Ditemukan {$total} siswa yang belum memiliki catatan Buku Induk:");
        $this->newLine();

        $this->table(
            ['No', 'Nama', 'NISN', 'NIS/NIPD'],
            $siswas->map(fn($s, $i) => [
                $i + 1,
                $s->nama,
                $s->nisn,
                $s->nipd ?? '-',
            ])->toArray()
        );

        $this->newLine();

        if ($isDryRun) {
            $this->comment('Mode dry-run aktif. Tidak ada data yang disimpan.');
            return self::SUCCESS;
        }

        if (!$this->option('force') && !$this->confirm("Buat {$total} catatan Buku Induk sekarang?", true)) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $created = 0;
        $skipped = 0;

        foreach ($siswas as $siswa) {
            // Double-check race condition: mungkin dibuat di iterasi sebelumnya
            if (BukuInduk::where('nisn', $siswa->nisn)->exists()) {
                $skipped++;
                $bar->advance();
                continue;
            }

            BukuInduk::create([
                'nisn'      => $siswa->nisn,
                'nama_ayah' => $siswa->nama_ayah ?? null,
                'nama_ibu'  => $siswa->nama_ibu ?? null,
            ]);

            $created++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Selesai! {$created} catatan Buku Induk berhasil dibuat." . ($skipped > 0 ? " ({$skipped} dilewati karena sudah ada)" : ''));
        $this->newLine();

        return self::SUCCESS;
    }
}
