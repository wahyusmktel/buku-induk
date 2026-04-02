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
        Schema::table('buku_induks', function (Blueprint $table) {
            // Data Ayah tambahan
            $table->string('nama_ayah', 200)->nullable()->after('beasiswa');
            $table->string('pekerjaan_ayah_bi', 100)->nullable()->after('agama_ayah');
            $table->string('pendidikan_ayah_bi', 100)->nullable()->after('pekerjaan_ayah_bi');
            // Data Ibu tambahan
            $table->string('nama_ibu', 200)->nullable()->after('pendidikan_ayah_bi');
            $table->string('pekerjaan_ibu_bi', 100)->nullable()->after('agama_ibu');
            $table->string('pendidikan_ibu_bi', 100)->nullable()->after('pekerjaan_ibu_bi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_induks', function (Blueprint $table) {
            $table->dropColumn([
                'nama_ayah',
                'pekerjaan_ayah_bi', 'pendidikan_ayah_bi',
                'nama_ibu',
                'pekerjaan_ibu_bi', 'pendidikan_ibu_bi',
            ]);
        });
    }
};
