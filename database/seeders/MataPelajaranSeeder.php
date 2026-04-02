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
            ['kelompok' => 'Muatan Kewilayahan', 'nama' => 'Seni Budaya dan Keterampilan', 'urutan' => 7],
            ['kelompok' => 'Muatan Kewilayahan', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'urutan' => 8],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Muatan Lokal', 'urutan' => 9],
            ['kelompok' => 'Muatan Lokal', 'nama' => 'Muatan Lokal 2', 'urutan' => 10],
        ];

        foreach ($mapels as $mapel) {
            \App\Models\MataPelajaran::firstOrCreate(
                ['nama' => $mapel['nama']],
                ['kelompok' => $mapel['kelompok'], 'urutan' => $mapel['urutan']]
            );
        }
    }
}
