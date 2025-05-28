<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecetaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(),
            'imagen' => 'images/default-img.jpg',
            'tipo' => $this->faker->randomElement(['Desayuno', 'Almuerzo', 'Cena', 'Postre']),
            'ingredientes' => $this->faker->paragraph(),
            'procedimiento' => $this->faker->text(500),
            'estado' => $this->faker->boolean() ? 0 : 1,
        ];
    }
}
