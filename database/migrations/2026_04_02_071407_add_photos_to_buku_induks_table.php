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
            $table->string('foto_1')->nullable()->after('telp_wali_bi');
            $table->string('foto_2')->nullable()->after('foto_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_induks', function (Blueprint $table) {
            $table->dropColumn(['foto_1', 'foto_2']);
        });
    }
};
