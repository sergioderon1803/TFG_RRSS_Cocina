<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // o bcrypt('password')
            'remember_token' => Str::random(10),
            'user_type' => $this->faker->boolean(20) ? 1 : 0, // 20% admins
        ];
    }
}