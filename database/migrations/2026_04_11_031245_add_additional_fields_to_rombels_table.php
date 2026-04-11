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
        Schema::table('rombels', function (Blueprint $table) {
            $table->enum('jenis_rombel', ['Kelas', 'Pilihan'])->default('Kelas')->after('tingkat');
            $table->string('kompetensi_keahlian')->nullable()->after('jenis_rombel');
            $table->string('kurikulum')->nullable()->after('kompetensi_keahlian');
            $table->uuid('guru_id')->nullable()->after('kurikulum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rombels', function (Blueprint $table) {
            $table->dropColumn(['jenis_rombel', 'kompetensi_keahlian', 'kurikulum', 'guru_id']);
        });
    }
};
