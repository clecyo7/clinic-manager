<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'birth_date' => fake()->date(),
            'address' => fake()->address(),
            'gender' => fake()->randomElement(['masculino', 'feminino', 'outro']),
            'psychologist_id' => User::factory(),
        ];
    }
}