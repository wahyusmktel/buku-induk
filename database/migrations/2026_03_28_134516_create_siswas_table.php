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
        Schema::create('siswas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Identitas Dasar
            $table->string('nama');
            $table->string('nipd')->nullable();
            $table->string('jk', 1)->nullable(); // L/P
            $table->string('nisn', 10)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('agama')->nullable();
            
            // Alamat
            $table->string('alamat')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('jenis_tinggal')->nullable();
            $table->string('alat_transportasi')->nullable();
            
            // Kontak
            $table->string('telepon')->nullable();
            $table->string('hp')->nullable();
            $table->string('email')->nullable();
            
            // Dokumen & Kesejahteraan
            $table->string('skhun')->nullable();
            $table->string('penerima_kps')->nullable();
            $table->string('no_kps')->nullable();
            
            // Data Ayah
            $table->string('nama_ayah')->nullable();
            $table->integer('tahun_lahir_ayah')->nullable();
            $table->string('jenjang_pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('nik_ayah')->nullable();
            
            // Data Ibu
            $table->string('nama_ibu')->nullable();
            $table->integer('tahun_lahir_ibu')->nullable();
            $table->string('jenjang_pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('nik_ibu')->nullable();
            
            // Data Wali
            $table->string('nama_wali')->nullable();
            $table->integer('tahun_lahir_wali')->nullable();
            $table->string('jenjang_pendidikan_wali')->nullable();
            $table->string('pekerjaan_wali')->nullable();
            $table->string('penghasilan_wali')->nullable();
            $table->string('nik_wali')->nullable();
            
            // Akademik & Registrasi
            $table->string('rombel_saat_ini')->nullable();
            $table->string('no_peserta_un')->nullable();
            $table->string('no_seri_ijazah')->nullable();
            $table->string('penerima_kip')->nullable();
            $table->string('nomor_kip')->nullable();
            $table->string('nama_di_kip')->nullable();
            $table->string('nomor_kks')->nullable();
            $table->string('no_registrasi_akta_lahir')->nullable();
            
            // Bank
            $table->string('bank')->nullable();
            $table->string('nomor_rekening_bank')->nullable();
            $table->string('rekening_atas_nama')->nullable();
            
            // PIP
            $table->string('layak_pip')->nullable();
            $table->string('alasan_layak_pip')->nullable();
            
            // Lainnya
            $table->text('kebutuhan_khusus')->nullable();
            $table->string('sekolah_asal')->nullable();
            $table->integer('anak_ke_berapa')->nullable();
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->string('no_kk')->nullable();
            
            // Periodik
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('lingkar_kepala', 5, 2)->nullable();
            $table->integer('jml_saudara_kandung')->nullable();
            $table->decimal('jarak_rumah_ke_sekolah_km', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
