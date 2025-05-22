<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RespuestaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'contenido' => $this->faker->sentence(),
            'id_receta' => 1,
            'id_user_respondido' => 1,
            'f_creacion' => now(),
        ];
    }
}
