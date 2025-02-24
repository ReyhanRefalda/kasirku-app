<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => $this->faker->sentence(2), // Bahasa Indonesia
            'kode' => $this->faker->unique()->bothify('KAT-##'),
        ];
    }
}
