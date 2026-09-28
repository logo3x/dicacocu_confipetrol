<?php

namespace Database\Factories\Do;

use App\Enums\Do\CumpleRegla;
use App\Enums\Do\ReglaSalvaVidas;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\EvaluacionRegla;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluacionRegla>
 */
class EvaluacionReglaFactory extends Factory
{
    protected $model = EvaluacionRegla::class;

    public function definition(): array
    {
        return [
            'evaluacion_id' => EvaluacionF14::factory(),
            'numero_regla' => fake()->randomElement(ReglaSalvaVidas::cases())->value,
            'cumple' => fake()->randomElement(CumpleRegla::cases())->value,
        ];
    }
}
