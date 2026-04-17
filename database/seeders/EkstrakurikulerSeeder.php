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
                'deskripsi' => 'Pendidikan kepanduan untuk melatih kemandirian, kedisiplinan, dan jiwa kepemimpinan siswa.',
            ],
            [
                'nama_ekstrakurikuler' => 'Tapak Suci',
                'deskripsi' => 'Seni bela diri khas Muhammadiyah yang melatih kekuaan fisik dan mental.',
            ],
            [
                'nama_ekstrakurikuler' => 'Hizbul Wathan',
                'deskripsi' => 'Kepanduan khas Muhammadiyah yang menanamkan jiwa patriotisme dan religius.',
            ],
            [
                'nama_ekstrakurikuler' => 'PMI / PMR',
                'deskripsi' => 'Palang Merah Remaja yang melatih keterampilan pertolongan pertama dan jiwa kemanusiaan.',
            ],
            [
                'nama_ekstrakurikuler' => 'Futsal',
                'deskripsi' => 'Kegiatan olahraga sepak bola dalam ruangan untuk melatih fisik, kerjasama tim, dan sportivitas.',
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
