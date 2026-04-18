<?php

// Dijalankan dengan: php artisan migrate

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('rombels', function (Blueprint $table) {
            $table->string('nama_wali_kelas')->nullable()->after('nama');
        });
    }
    public function down(): void {
        Schema::table('rombels', function (Blueprint $table) {
            $table->dropColumn('nama_wali_kelas');
        });
    }
};
