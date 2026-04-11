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
        Schema::create('prestasi_ekstrakurikulers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignUuid('ekstrakurikuler_id')->constrained('ekstrakurikulers')->onDelete('cascade');
            $table->unsignedTinyInteger('kelas');
            $table->unsignedTinyInteger('semester'); // 1 or 2
            $table->string('predikat', 50)->nullable(); // A, B, C, or Baik, Sangat Baik
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_ekstrakurikulers');
    }
};
