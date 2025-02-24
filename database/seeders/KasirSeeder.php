<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KasirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('Reyhann1'),
            'role' => 'kasir',
            'tipe_pelanggan' => null,
        ]);
    }
}
