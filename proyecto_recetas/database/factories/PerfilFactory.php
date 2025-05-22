<?php

namespace Database\Factories;

use App\Models\Perfil;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerfilFactory extends Factory
{
    protected $model = Perfil::class;

    public function definition(): array
    {
        return [
            // Este campo 'id_user' se debe asignar manualmente desde el seeder
            'name' => $this->faker->unique()->userName(),
            'img_perfil' => 'default_profile.png',
            'img_banner' => 'default_banner.jpg',
            'biografia' => $this->faker->sentence(12),
        ];
    }
}
