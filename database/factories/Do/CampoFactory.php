<?php

namespace Database\Factories\Do;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campo>
 */
class CampoFactory extends Factory
{
    protected $model = Campo::class;

    public function definition(): array
    {
        return [
            'contrato_id' => Contrato::factory(),
            'nombre' => fake()->unique()->randomElement(['Cusiana', 'Quifa', 'Caño Limón', 'Rubiales', 'Castilla']).' '.fake()->unique()->numberBetween(1, 999),
            'codigo' => fake()->bothify('CMP-##'),
            'activo' => true,
        ];
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }
}
