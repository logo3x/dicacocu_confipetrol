<?php

namespace App\Models\Do;

use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;
use App\Models\User;
use Database\Factories\Do\ProcedimientoDoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Procedimiento de la Matriz Integral de Disciplina Operativa (DICACOCU).
 */
class ProcedimientoDo extends Model
{
    /** @use HasFactory<ProcedimientoDoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'do_procedimientos';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_identificacion' => 'date',
            'fecha_limite_estandarizacion' => 'date',
            'fecha_programada_codificacion' => 'date',
            'fecha_codificacion' => 'date',
            'fecha_ultima_divulgacion' => 'date',
            'fecha_programada_verificacion' => 'date',
            'fecha_ejecutada_verificacion' => 'date',
            'amenaza_riesgo_critico' => 'boolean',
            'amenaza_equipos_criticos' => 'boolean',
            'amenaza_impacto_ambiental' => 'boolean',
            'amenaza_antecedentes' => 'boolean',
            'amenaza_afecta_servicio' => 'boolean',
            'amenaza_no_rutinaria' => 'boolean',
            'codificado' => 'boolean',
            'prioridad' => PrioridadDo::class,
            'criterio_opt' => CriterioOpt::class,
            'cobertura_socializacion' => 'decimal:2',
            'puntaje_opt' => 'decimal:2',
        ];
    }

    /** @return HasMany<EvaluacionF14, $this> */
    public function evaluaciones(): HasMany
    {
        return $this->hasMany(EvaluacionF14::class, 'procedimiento_id');
    }

    /** @return BelongsTo<User, $this> */
    public function responsableArea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_area_id');
    }

    /** @return BelongsTo<User, $this> */
    public function responsableCodificacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_codificacion_id');
    }

    /** @return BelongsTo<User, $this> */
    public function observadorOperativo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observador_operativo_id');
    }

    /** @return BelongsTo<User, $this> */
    public function observadorHseq(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observador_hseq_id');
    }

    /** @return BelongsTo<User, $this> */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function estaEstandarizado(): bool
    {
        return filled($this->codigo_asignado);
    }
}
