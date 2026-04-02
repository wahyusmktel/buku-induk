<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $prestasis = DB::table('prestasi_belajars')->get();
        if ($prestasis->count() > 0) {
            $mapelFields = [
                'nilai_agama' => 'Pendidikan Agama dan Budi Pekerti',
                'nilai_pkn' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'nilai_bindo' => 'Bahasa Indonesia',
                'nilai_mtk' => 'Matematika',
                'nilai_ipa' => 'Ilmu Pengetahuan Alam',
                'nilai_ips' => 'Ilmu Pengetahuan Sosial',
                'nilai_sbk' => 'Seni Budaya dan Keterampilan',
                'nilai_pjok' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'nilai_mulok' => 'Muatan Lokal',
                'nilai_mulok2' => 'Muatan Lokal 2'
            ];
            
            $mapelIds = [];
            $urutan = 1;
            foreach ($mapelFields as $col => $nama) {
                $check = DB::table('mata_pelajarans')->where('nama', $nama)->first();
                if (!$check) {
                    $id = Str::uuid()->toString();
                    DB::table('mata_pelajarans')->insert([
                        'id' => $id,
                        'nama' => $nama,
                        'kelompok' => str_starts_with($nama, 'Muatan') ? 'Muatan Lokal' : 'Kelompok Umum',
                        'urutan' => $urutan++,
                        'is_aktif' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $mapelIds[$col] = $id;
                } else {
                    $mapelIds[$col] = $check->id;
                }
            }

            foreach ($prestasis as $p) {
                foreach ($mapelFields as $col => $nama) {
                    if (isset($p->$col) && $p->$col !== null) {
                        DB::table('prestasi_nilais')->insert([
                            'id' => Str::uuid()->toString(),
                            'prestasi_belajar_id' => $p->id,
                            'mata_pelajaran_id' => $mapelIds[$col],
                            'nilai' => $p->$col,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        Schema::table('prestasi_belajars', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_agama', 'nilai_pkn', 'nilai_bindo', 'nilai_mtk',
                'nilai_ipa', 'nilai_ips', 'nilai_sbk', 'nilai_pjok',
                'nilai_mulok', 'nilai_mulok2',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('prestasi_belajars', function (Blueprint $table) {
            $table->decimal('nilai_agama', 5, 2)->nullable();
            $table->decimal('nilai_pkn', 5, 2)->nullable();
            $table->decimal('nilai_bindo', 5, 2)->nullable();
            $table->decimal('nilai_mtk', 5, 2)->nullable();
            $table->decimal('nilai_ipa', 5, 2)->nullable();
            $table->decimal('nilai_ips', 5, 2)->nullable();
            $table->decimal('nilai_sbk', 5, 2)->nullable();
            $table->decimal('nilai_pjok', 5, 2)->nullable();
            $table->decimal('nilai_mulok', 5, 2)->nullable();
            $table->decimal('nilai_mulok2', 5, 2)->nullable();
        });
    }
};
