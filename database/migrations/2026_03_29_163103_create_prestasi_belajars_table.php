<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_belajars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Link to buku_induks
            $table->uuid('buku_induk_id');
            $table->foreign('buku_induk_id')->references('id')->on('buku_induks')->cascadeOnDelete();
            
            // Period
            $table->tinyInteger('kelas'); // 1-6
            $table->tinyInteger('semester'); // 1 or 2
            $table->string('tahun_pelajaran'); // e.g. "2024/2025"

            // Academic Scores (nullable — manually entered)
            $table->decimal('nilai_agama', 5, 2)->nullable();
            $table->decimal('nilai_pkn', 5, 2)->nullable();
            $table->decimal('nilai_bindo', 5, 2)->nullable();
            $table->decimal('nilai_mtk', 5, 2)->nullable();
            $table->decimal('nilai_ipa', 5, 2)->nullable();
            $table->decimal('nilai_ips', 5, 2)->nullable();
            $table->decimal('nilai_sbk', 5, 2)->nullable();
            $table->decimal('nilai_pjok', 5, 2)->nullable();
            $table->decimal('nilai_mulok', 5, 2)->nullable();
            $table->decimal('nilai_mulok2', 5, 2)->nullable(); // Optional 2nd mulok
            
            // Computed/stored results
            $table->decimal('jumlah_nilai', 7, 2)->nullable();
            $table->decimal('rata_rata', 5, 2)->nullable();
            $table->integer('peringkat')->nullable();
            
            // Personality
            $table->string('sikap')->nullable();      // Baik/Cukup/Kurang
            $table->string('kerajinan')->nullable();
            $table->string('kebersihan_kerapian')->nullable();
            
            // Attendance
            $table->integer('hadir_sakit')->default(0);
            $table->integer('hadir_izin')->default(0);
            $table->integer('hadir_alpha')->default(0);
            
            // Promotion
            $table->string('keterangan_kenaikan')->nullable(); // Naik / Tidak Naik
            $table->date('tgl_keputusan_kenaikan')->nullable();
            $table->text('catatan_guru')->nullable();
            
            $table->timestamps();
            
            // Unique constraint: one record per student per class per semester
            $table->unique(['buku_induk_id', 'kelas', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_belajars');
    }
};
