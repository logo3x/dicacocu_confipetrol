<?php

namespace App\Models\Do;

use App\Enums\Do\CriterioOpt;
use App\Models\User;
use Database\Factories\Do\EvaluacionF14Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Evaluación del formato de Acompañamiento y Verificación de Actividades (HSEQ-GCA1-F-14).
 */
class EvaluacionF14 extends Model
{
    /** @use HasFactory<EvaluacionF14Factory> */
    use HasFactory, SoftDeletes;

    protected $table = 'do_evaluaciones_f14';

    protected $guarded = [];

    /** Campos booleanos de las 11 preguntas del checklist. */
    public const PREGUNTAS_CHECKLIST = ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11'];

    protected function casts(): array
    {
        return [
            'fecha_ejecucion' => 'date',
            'pasos_observados' => 'array',
            'q1' => 'boolean',
            'q2' => 'boolean',
            'q3' => 'boolean',
            'q4' => 'boolean',
            'q5' => 'boolean',
            'q6' => 'boolean',
            'q7' => 'boolean',
            'q8' => 'boolean',
            'q9' => 'boolean',
            'q10' => 'boolean',
            'q11' => 'boolean',
            'aplica_inspeccion_gerencial' => 'boolean',
            'criterio_opt' => CriterioOpt::class,
            'subtotal' => 'decimal:2',
            'puntaje_opt' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<ProcedimientoDo, $this> */
    public function procedimiento(): BelongsTo
    {
        return $this->belongsTo(ProcedimientoDo::class, 'procedimiento_id');
    }

    /** @return HasMany<EvaluacionRegla, $this> */
    public function reglas(): HasMany
    {
        return $this->hasMany(EvaluacionRegla::class, 'evaluacion_id');
    }

    /** @return HasMany<EvaluacionAccion, $this> */
    public function acciones(): HasMany
    {
        return $this->hasMany(EvaluacionAccion::class, 'evaluacion_id');
    }

    /** @return BelongsTo<User, $this> */
    public function observador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observador_id');
    }

    /** @return BelongsTo<User, $this> */
    public function acompanante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acompanante_id');
    }

    /** @return BelongsTo<User, $this> */
    public function responsableArea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_area_id');
    }

    /**
     * Respuestas del checklist indexadas por campo.
     *
     * @return array<string, bool>
     */
    public function respuestasChecklist(): array
    {
        $respuestas = [];

        foreach (self::PREGUNTAS_CHECKLIST as $campo) {
            $respuestas[$campo] = (bool) $this->{$campo};
        }

        return $respuestas;
    }
}
