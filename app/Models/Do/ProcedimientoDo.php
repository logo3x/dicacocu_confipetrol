<?php

namespace App\Models\Do;

use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;
use App\Models\Documento;
use App\Models\User;
use Database\Factories\Do\ProcedimientoDoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

/**
 * Procedimiento de la Matriz Integral de Disciplina Operativa (DICACOCU).
 */
class ProcedimientoDo extends Model
{
    /** @use HasFactory<ProcedimientoDoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'do_procedimientos';

    /**
     * Declarados explícitamente porque el historial de cambios se construye a
     * partir de ellos.
     *
     * @var list<string>
     */
    protected $fillable = [
        ...self::CAMPOS_AUDITADOS,
        'created_by',
        'plazo_estandarizacion_meses',
        'fecha_limite_estandarizacion',
        'frecuencia_verificacion_meses',
    ];

    /** Campos cuyo cambio queda registrado en el historial. */
    public const CAMPOS_AUDITADOS = [
        'anio_ciclo', 'contrato_id', 'campo_id',
        'nombre_actividad', 'categoria_cargo', 'fecha_identificacion', 'personas_involucradas',
        'amenaza_riesgo_critico', 'amenaza_equipos_criticos', 'amenaza_impacto_ambiental',
        'amenaza_antecedentes', 'amenaza_afecta_servicio', 'amenaza_no_rutinaria',
        'puntaje_prioridad', 'prioridad',
        'codificado', 'fecha_programada_codificacion', 'fecha_codificacion',
        'responsable_codificacion_id', 'codigo_asignado', 'titulo_procedimiento',
        'version_actual', 'ubicacion_acceso', 'documento_id',
        'fecha_ultima_divulgacion', 'personas_socializadas', 'cobertura_socializacion',
        'responsable_area_id', 'fecha_programada_verificacion', 'fecha_ejecutada_verificacion',
        'observador_operativo_id', 'observador_hseq_id', 'puntaje_opt', 'criterio_opt',
    ];

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

    /** @return BelongsTo<Contrato, $this> */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /** @return BelongsTo<Campo, $this> */
    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class, 'campo_id');
    }

    /**
     * Categorías ya usadas, para sugerirlas al escribir y que no proliferen
     * variantes del mismo cargo.
     *
     * @return array<int, string>
     */
    public static function categoriasRegistradas(): array
    {
        return static::query()
            ->whereNotNull('categoria_cargo')
            ->distinct()
            ->orderBy('categoria_cargo')
            ->pluck('categoria_cargo')
            ->all();
    }

    /**
     * Documento del repositorio donde vive este procedimiento ya
     * estandarizado.
     *
     * @return BelongsTo<Documento, $this>
     */
    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class, 'documento_id');
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

    /**
     * Historial de cambios del procedimiento.
     *
     * Lo alimenta ProcedimientoDoObserver, que registra explícitamente los
     * valores anterior y nuevo de cada campo.
     *
     * @return MorphMany<Activity, $this>
     */
    public function historial(): MorphMany
    {
        return $this->morphMany(Activity::class, 'subject')->latest('id');
    }
}
