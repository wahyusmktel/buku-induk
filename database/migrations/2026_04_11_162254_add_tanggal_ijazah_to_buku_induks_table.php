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
            $table->date('tanggal_ijazah')->nullable()->after('no_ijazah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_induks', function (Blueprint $table) {
            $table->dropColumn('tanggal_ijazah');
        });
    }
};
