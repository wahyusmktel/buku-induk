<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Setup initial roles first before assigning
        $this->call([
            RoleAndPermissionSeeder::class,
            MataPelajaranSeeder::class,
            EkstrakurikulerSeeder::class,
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@sdmuhgisting.sch.id'],
            [
                'name' => 'Admin Sistem',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]
        );

        // Assign the role
        if (!$admin->hasRole('Super Admin')) {
            $admin->assignRole('Super Admin');
        }
    }
}
