<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapels = [
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Pendidikan Agama dan Budi Pekerti', 'urutan' => 1],
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan', 'urutan' => 2],
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Bahasa Indonesia', 'urutan' => 3],
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Matematika', 'urutan' => 4],
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Ilmu Pengetahuan Alam', 'urutan' => 5],
            ['kelompok' => 'Muatan Nasional', 'nama' => 'Ilmu Pengetahuan Sosial', 'urutan' => 6],
            ['kelompok' => 'Muatan Kewilayahan', 'nama' => 'Seni Budaya dan Prakarya', 'urutan' => 7],
            ['kelompok' => 'Muatan Kewilayahan', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'urutan' => 8],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Bahasa Inggris', 'urutan' => 9],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Bahasa Lampung', 'urutan' => 10],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Kemuhammadiyahan', 'urutan' => 11],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Bahasa Arab', 'urutan' => 12],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Teknologi Informasi dan Komunikasi', 'urutan' => 13],
        ];

        foreach ($mapels as $mapel) {
            \App\Models\MataPelajaran::updateOrCreate(
                ['nama' => $mapel['nama']],
                [
                    'kelompok' => $mapel['kelompok'],
                    'urutan' => $mapel['urutan'],
                    'is_aktif' => true
                ]
            );
        }
    }
}
