<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kategori;

class BarangFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_barang' => $this->faker->unique()->bothify('BRG-#####'),
            'nama_barang' => $this->faker->words(2, true),
            'tanggal_kedaluarsa' => $this->faker->dateTimeBetween('now', '+2 years')->format('Y-m-d'),
            'tanggal_pembelian' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'kategori_id' => Kategori::factory(),
            'harga_jual' => $this->faker->randomFloat(2, 5000, 500000), // Harga jual acak antara 5000 - 500000
            'stock_barang' => $this->faker->numberBetween(1, 100), // Stok barang antara 1 - 100
        ];
    }
}
