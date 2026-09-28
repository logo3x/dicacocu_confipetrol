<?php

namespace Database\Factories\Do;

use App\Models\Do\Contrato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contrato>
 */
class ContratoFactory extends Factory
{
    protected $model = Contrato::class;

    public function definition(): array
    {
        return [
            'codigo' => fake()->unique()->bothify('CTR-###'),
            'nombre' => fake()->randomElement(['Ecopetrol Cusiana', 'Frontera Quifa', 'Oxy Caño Limón']).' '.fake()->unique()->numberBetween(1, 999),
            'cliente' => fake()->randomElement(['Ecopetrol', 'Frontera Energy', 'Occidental']),
            'activo' => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }
}
