<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PemilikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pemilik Toko',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Reyhann1'),
            'role' => 'pemilik',
            'tipe_pelanggan' => null,
        ]);
    }
}
