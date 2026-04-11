<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrasi_siswas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('jenis_registrasi', ['Tamat Belajar', 'Pindah Sekolah', 'Keluar Sekolah', 'Lainnya']);
            $table->date('tanggal')->nullable();
            $table->string('tujuan_sekolah')->nullable();
            $table->string('tujuan_kelas')->nullable();
            $table->text('alasan_catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi_siswas');
    }
};
