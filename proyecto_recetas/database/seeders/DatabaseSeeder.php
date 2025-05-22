<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create()->each(function ($user) {
            \App\Models\Perfil::factory()->create([
                'id_user' => $user->id
            ]);
        });


        \App\Models\Receta::factory(20)->create([
            'autor_receta' => \App\Models\User::inRandomOrder()->first()->id,
        ]);

        \App\Models\Comentario::factory(40)->create([
            'id_user' => \App\Models\User::inRandomOrder()->first()->id,
            'id_receta' => \App\Models\Receta::inRandomOrder()->first()->id,
        ]);

        \App\Models\Respuesta::factory(30)->create([
            'id_user' => \App\Models\User::inRandomOrder()->first()->id,
            'id_user_respondido' => \App\Models\User::inRandomOrder()->first()->id,
            'id_comentario' => \App\Models\Comentario::inRandomOrder()->first()->id,
            'id_receta' => \App\Models\Receta::inRandomOrder()->first()->id,
        ]);

        // Gustar y guardar recetas
        foreach (\App\Models\User::all() as $user) {
            $recetas = \App\Models\Receta::inRandomOrder()->take(3)->get();
            foreach ($recetas as $receta) {
                DB::table('gustar_receta')->insert([
                    'id_user' => $user->id,
                    'id_receta' => $receta->id,
                    'f_gustar' => now(),
                ]);
                DB::table('guardar_receta')->insert([
                    'id_user' => $user->id,
                    'id_receta' => $receta->id,
                    'f_guardar' => now(),
                ]);
            }
        }

        // Seguimiento entre usuarios
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $seguidores = $users->where('id', '!=', $user->id)->random(2);
            foreach ($seguidores as $seguidor) {
                DB::table('seguir_usuario')->insert([
                    'id_user' => $user->id,
                    'id_seguidor' => $seguidor->id,
                    'f_seguimiento' => now(),
                ]);
            }
        }
    }

}
