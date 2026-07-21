<?php

namespace Database\Factories;

use App\Models\Compromiso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Compromiso>
 */
class CompromisoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(),
            'fecha_limite' => fake()->dateTimeBetween('now', '+2 months'),
            'created_by' => User::factory(),
        ];
    }

    public function cumplido(): static
    {
        return $this->state(fn (array $attributes) => [
            'cumplido_at' => now(),
            'cumplido_por' => User::factory(),
        ]);
    }

    public function vencido(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_limite' => fake()->dateTimeBetween('-2 months', '-1 day'),
        ]);
    }
}
