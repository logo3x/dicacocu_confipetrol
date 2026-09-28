<?php

namespace Database\Factories\Do;

use App\Models\Do\EvaluacionAccion;
use App\Models\Do\EvaluacionF14;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluacionAccion>
 */
class EvaluacionAccionFactory extends Factory
{
    protected $model = EvaluacionAccion::class;

    public function definition(): array
    {
        return [
            'evaluacion_id' => EvaluacionF14::factory(),
            'accion' => fake()->sentence(),
            'fecha_cierre' => now()->addDays(fake()->numberBetween(5, 45)),
        ];
    }
}
