<?php

namespace Database\Factories\Do;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use App\Models\Do\ProcedimientoDo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProcedimientoDo>
 */
class ProcedimientoDoFactory extends Factory
{
    protected $model = ProcedimientoDo::class;

    public function definition(): array
    {
        return [
            'anio_ciclo' => now()->year,
            'contrato_id' => Contrato::factory(),
            'campo_id' => fn (array $atributos) => Campo::factory()->create([
                'contrato_id' => $atributos['contrato_id'],
            ])->id,
            'nombre_actividad' => fake()->sentence(4),
            'fecha_identificacion' => now()->subDays(fake()->numberBetween(1, 120)),
            'personas_involucradas' => fake()->numberBetween(3, 30),
            'amenaza_riesgo_critico' => false,
            'amenaza_equipos_criticos' => false,
            'amenaza_impacto_ambiental' => false,
            'amenaza_antecedentes' => false,
            'amenaza_afecta_servicio' => false,
            'amenaza_no_rutinaria' => false,
        ];
    }

    /** Actividad de prioridad alta: riesgo crítico + antecedentes + equipos críticos (65+... = 80). */
    public function prioridadAlta(): static
    {
        return $this->state(fn () => [
            'amenaza_riesgo_critico' => true,
            'amenaza_equipos_criticos' => true,
            'amenaza_impacto_ambiental' => true,
            'amenaza_antecedentes' => true,
        ]);
    }

    /** Actividad de prioridad media: 60 puntos. */
    public function prioridadMedia(): static
    {
        return $this->state(fn () => [
            'amenaza_riesgo_critico' => true,
            'amenaza_antecedentes' => true,
            'amenaza_afecta_servicio' => true,
        ]);
    }

    public function estandarizado(): static
    {
        return $this->state(fn () => [
            'codificado' => true,
            'codigo_asignado' => 'HSEQ-'.fake()->unique()->bothify('??##-P-##'),
            'titulo_procedimiento' => fake()->sentence(5),
            'version_actual' => fake()->numberBetween(1, 15),
            'ubicacion_acceso' => 'SharePoint / HSEQ / Procedimientos',
            'fecha_codificacion' => now()->subDays(fake()->numberBetween(1, 60)),
        ]);
    }
}
