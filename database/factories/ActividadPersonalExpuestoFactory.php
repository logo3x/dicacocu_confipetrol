<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\ActividadPersonalExpuesto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActividadPersonalExpuesto>
 */
class ActividadPersonalExpuestoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'actividad_id' => Actividad::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function socializada(): static
    {
        return $this->state(function (array $attributes) {
            $fecha = fake()->dateTimeBetween('-11 months', 'now');

            return [
                'fecha_socializacion' => $fecha,
                'fecha_vencimiento' => (clone $fecha)->modify('+1 year'),
                'socializado_por' => User::factory(),
            ];
        });
    }

    public function vencida(): static
    {
        return $this->state(function (array $attributes) {
            $fecha = fake()->dateTimeBetween('-3 years', '-13 months');

            return [
                'fecha_socializacion' => $fecha,
                'fecha_vencimiento' => (clone $fecha)->modify('+1 year'),
                'socializado_por' => User::factory(),
            ];
        });
    }
}
