<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::factory(5)->create()->each(function ($kategori) {
            Barang::factory(2)->create(['kategori_id' => $kategori->id]);
        });
    }
}
