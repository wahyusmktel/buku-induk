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
        $data = [
            [
                'nama_ekstrakurikuler' => 'Pramuka',
                'deskripsi' => 'Pendidikan kepanduan untuk melatih kemandirian, kedisiplinan, dan jiwa kepemimpinan siswa.',
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

        foreach ($data as $item) {
            Ekstrakurikuler::create($item);
        }
    }
}
