<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use Illuminate\Database\Seeder;

class EkstrakurikulerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ekskuls = [
            [
                'nama_ekstrakurikuler' => 'Pramuka',
                'deskripsi' => 'Ekstrakurikuler wajib yang melatih disiplin, kemandirian, dan kepemimpinan.',
            ],
            [
                'nama_ekstrakurikuler' => 'Tapak Suci',
                'deskripsi' => 'Seni bela diri khas Muhammadiyah yang melatih kekuaan fisik dan mental.',
            ],
            [
                'nama_ekstrakurikuler' => 'Hizbul Wathan',
                'deskripsi' => 'Kepanduan khas Muhammadiyah yang menanamkan jiwa patriotisme dan religius.',
            ],
        ];

        foreach ($ekskuls as $ekskul) {
            Ekstrakurikuler::updateOrCreate(
                ['nama_ekstrakurikuler' => $ekskul['nama_ekstrakurikuler']],
                ['deskripsi' => $ekskul['deskripsi']]
            );
        }
    }
}
