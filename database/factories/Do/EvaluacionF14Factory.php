<?php

namespace Database\Factories\Do;

use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluacionF14>
 */
class EvaluacionF14Factory extends Factory
{
    protected $model = EvaluacionF14::class;

    public function definition(): array
    {
        return [
            'procedimiento_id' => ProcedimientoDo::factory(),
            'fecha_ejecucion' => now()->subDays(fake()->numberBetween(1, 60)),
            'campo' => fake()->randomElement(['Cusiana', 'Quifa']),
            'area' => fake()->randomElement(['Mantenimiento', 'Operaciones']),
            'nombre_actividad_observada' => fake()->sentence(4),
            'observador_id' => User::factory(),
            'cargo_observador' => 'Supervisor HSEQ',
            'pasos_observados' => ['Preparar herramienta', 'Aislar energía', 'Ejecutar'],
            'pasos_segun_procedimiento' => 3,
            'pasos_en_observacion' => 3,
        ];
    }

    /** Todas las respuestas afirmativas: 11 × 6,37 + 30 = 100,07. */
    public function completa(): static
    {
        return $this->state(function () {
            $estado = [];

            foreach (EvaluacionF14::PREGUNTAS_CHECKLIST as $campo) {
                $estado[$campo] = true;
            }

            return $estado;
        });
    }

    public function conInspeccionGerencial(): static
    {
        return $this->state(fn () => [
            'aplica_inspeccion_gerencial' => true,
            'hallazgos_positivos' => fake()->sentence(),
            'desvios_oportunidades' => fake()->sentence(),
        ]);
    }
}
