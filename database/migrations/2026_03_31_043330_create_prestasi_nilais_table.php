<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi_nilais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('prestasi_belajar_id');
            $table->uuid('mata_pelajaran_id');
            $table->decimal('nilai', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('prestasi_belajar_id')->references('id')->on('prestasi_belajars')->cascadeOnDelete();
            $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajarans')->cascadeOnDelete();
            
            $table->unique(['prestasi_belajar_id', 'mata_pelajaran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi_nilais');
    }
};
