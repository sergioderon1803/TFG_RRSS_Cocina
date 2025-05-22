<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contenido' => $this->faker->sentence(),
            'f_creacion' => now(),
        ];
    }
}
