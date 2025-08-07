<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus user yang mungkin sudah ada
        User::truncate();

        // Buat satu user admin dengan data yang kita tentukan
        User::factory()->create([
            'name' => 'Admin CRS',
            'npk' => '12345678',
            'email' => 'admin.crs@perusahaan.com',
            'password' => Hash::make('password'), // Ganti 'password' dengan yang lebih aman nanti
        ]);
    }
}