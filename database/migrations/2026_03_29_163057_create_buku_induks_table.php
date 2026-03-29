<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_induks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Link to student — use NISN as the permanent cross-year identifier
            $table->string('nisn', 10)->unique()->nullable();
            $table->string('no_induk')->nullable(); // Nomor urut buku induk sekolah
            
            // Extended Identity (not in Dapodik)
            $table->string('nama_panggilan')->nullable();
            $table->string('kewarganegaraan')->default('WNI');
            $table->string('bahasa_sehari_hari')->nullable();
            $table->string('golongan_darah', 5)->nullable(); // A, B, AB, O
            $table->text('riwayat_penyakit')->nullable();
            $table->integer('jml_saudara_tiri')->default(0);
            $table->integer('jml_saudara_angkat')->default(0);
            $table->string('bertempat_tinggal_dengan')->nullable(); // Orang Tua / Wali / Asrama
            
            // School Entry History
            $table->date('tgl_masuk_sekolah')->nullable();
            $table->string('asal_masuk_sekolah')->nullable(); // TK/Paud/Rumah
            $table->string('nama_tk_asal')->nullable();
            
            // Transfer In
            $table->string('pindah_dari')->nullable(); // Name of originating school
            $table->string('kelas_pindah_masuk')->nullable(); // Which grade they entered from
            $table->date('tgl_pindah_masuk')->nullable();
            
            // Exit / Transfer Out / Graduation
            $table->date('tgl_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->string('no_ijazah')->nullable();
            $table->string('lanjut_ke')->nullable(); // Next school name
            $table->text('beasiswa')->nullable(); // Scholarship notes

            // Extended Parent Data (full, not just year)
            $table->string('tempat_lahir_ayah')->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('agama_ayah')->nullable();
            $table->string('kewarganegaraan_ayah')->default('WNI')->nullable();
            $table->text('alamat_ayah')->nullable();
            
            $table->string('tempat_lahir_ibu')->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('agama_ibu')->nullable();
            $table->string('kewarganegaraan_ibu')->default('WNI')->nullable();
            $table->text('alamat_ibu')->nullable();

            // Extended Wali Data
            $table->string('nama_wali_bi')->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->string('pekerjaan_wali_bi')->nullable();
            $table->string('pendidikan_wali_bi')->nullable();
            $table->text('alamat_wali_bi')->nullable();
            $table->string('telp_wali_bi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_induks');
    }
};
