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
        $this->call([
            KantinSeeder::class,
        ]);

        // Akun Admin Stan (Soto & Bakso)
        User::create([
            'name' => 'Admin Stan Soto',
            'email' => 'admin.soto@wikrama.sch.id',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'stand_id' => 1,
        ]);

        // Akun Siswa (Login menggunakan NIS)
        User::create([
            'name' => 'Siswa Fakhri',
            'email' => 'fakhri@wikrama.sch.id',
            'nis' => '12209001',
            'password' => bcrypt('password'),
            'is_admin' => false,
        ]);
    }
}
