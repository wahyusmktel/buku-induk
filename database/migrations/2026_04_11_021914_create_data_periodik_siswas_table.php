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
        Schema::create('data_periodik_siswas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->integer('jml_saudara_kandung')->nullable();
            $table->integer('jml_saudara_tiri')->nullable();
            $table->integer('jml_saudara_angkat')->nullable();
            $table->string('bahasa_sehari_hari')->nullable();
            $table->text('alamat_tinggal')->nullable();
            $table->string('bertempat_tinggal_pada')->nullable();
            $table->string('jarak_tempat_tinggal_ke_sekolah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_periodik_siswas');
    }
};
