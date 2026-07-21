<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Actividad>
 */
class ActividadFactory extends Factory
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
            'contrato' => fake()->bothify('CONT-####'),
            'campo' => fake()->city(),
            'descripcion' => fake()->paragraph(),
            'personal_expuesto' => fake()->numberBetween(1, 40),
            'valoracion_amenaza' => fake()->numberBetween(0, 100),
            'fecha_identificacion' => fake()->dateTimeBetween('-1 year', 'now'),
            'created_by' => User::factory(),
        ];
    }

    public function amenazaBaja(): static
    {
        return $this->state(fn (array $attributes) => ['valoracion_amenaza' => fake()->numberBetween(80, 100)]);
    }

    public function amenazaMedia(): static
    {
        return $this->state(fn (array $attributes) => ['valoracion_amenaza' => fake()->numberBetween(60, 79)]);
    }

    public function amenazaAlta(): static
    {
        return $this->state(fn (array $attributes) => ['valoracion_amenaza' => fake()->numberBetween(0, 59)]);
    }
}
