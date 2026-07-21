<?php

namespace Database\Factories;

use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcompanamientoVerificacion>
 */
class AcompanamientoVerificacionFactory extends Factory
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
            'fecha_ejecucion' => fake()->dateTimeBetween('-6 months', 'now'),
            'campo' => fake()->city(),
            'area' => fake()->randomElement(['Producción', 'Mantenimiento', 'HSEQ', 'Perforación']),
            'tipo_verificacion' => fake()->randomElement(['verificacion_cumplimiento_do', 'inspeccion_gerencial_caminar_planta']),
            'observador_id' => User::factory(),
            'pasos_observados' => [
                'Verificación de EPP',
                'Revisión del procedimiento en sitio',
                'Ejecución de la actividad',
            ],
            'created_by' => User::factory(),
        ];
    }

    /**
     * Simula el checklist completo con una cantidad dada de respuestas "Sí" (0-11)
     * y una coincidencia de pasos dada, para llegar a un puntaje OPT determinado.
     */
    public function conChecklist(int $preguntasEnSi, bool $pasosCoinciden = true): static
    {
        $preguntas = collect(AcompanamientoVerificacion::PREGUNTAS_CHECKLIST)
            ->mapWithKeys(fn (string $campo, int $i) => [$campo => $i < $preguntasEnSi])
            ->all();

        return $this->state(fn (array $attributes) => [
            ...$preguntas,
            'pasos_segun_procedimiento' => 10,
            'pasos_en_observacion' => $pasosCoinciden ? 10 : 5,
        ]);
    }

    public function excelente(): static
    {
        return $this->conChecklist(11, true);
    }

    public function bueno(): static
    {
        return $this->conChecklist(9, true);
    }

    public function regular(): static
    {
        return $this->conChecklist(11, false);
    }

    public function deficiente(): static
    {
        return $this->conChecklist(3, false);
    }

    public function detenida(): static
    {
        return $this->state(fn (array $attributes) => [
            'actividad_detenida' => true,
            'motivo_detencion' => fake()->sentence(),
        ]);
    }
}
