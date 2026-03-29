<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('nama_panggilan')->nullable()->after('nama');
            $table->string('kewarganegaraan')->default('WNI')->nullable()->after('agama');
            $table->string('bahasa_sehari_hari')->nullable()->after('kewarganegaraan');
            $table->string('golongan_darah', 5)->nullable()->after('bahasa_sehari_hari');
            $table->text('riwayat_penyakit')->nullable()->after('golongan_darah');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nama_panggilan',
                'kewarganegaraan',
                'bahasa_sehari_hari',
                'golongan_darah',
                'riwayat_penyakit',
            ]);
        });
    }
};
