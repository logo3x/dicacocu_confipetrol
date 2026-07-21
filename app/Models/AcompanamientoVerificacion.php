<?php

namespace App\Models;

use App\Enums\AnalisisActividad;
use App\Enums\ClasificacionOpt;
use Database\Factories\AcompanamientoVerificacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcompanamientoVerificacion extends Model
{
    /** @use HasFactory<AcompanamientoVerificacionFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'acompanamientos_verificacion';

    /**
     * Valor porcentual de cada una de las 11 preguntas SI/NO (70% / 11 ≈ 6.37%).
     */
    public const VALOR_PREGUNTA = 6.37;

    /**
     * Valor porcentual de la pregunta 12 (coincidencia de pasos) cuando aplica.
     */
    public const VALOR_PREGUNTA_12 = 30;

    public const PREGUNTAS_CHECKLIST = [
        'q1_procedimiento_disponible',
        'q2_usa_epp_correctamente',
        'q3_identifica_peligros_riesgos',
        'q4_herramientas_disponibles',
        'q5_area_limpia_ordenada',
        'q6_aplica_controles',
        'q7_procedimiento_actualizado',
        'q8_procedimiento_facil_entendimiento',
        'q9_procedimiento_divulgado',
        'q10_personal_capacitado_certificado',
        'q11_personal_mostro_habilidad',
    ];

    protected $fillable = [
        'actividad_id',
        'fecha_ejecucion',
        'campo',
        'area',
        'responsable_area_id',
        'tipo_verificacion',
        'observador_id',
        'cargo_observador',
        'acompanante_id',
        'cargo_acompanante',
        'pasos_observados',
        ...self::PREGUNTAS_CHECKLIST,
        'pasos_segun_procedimiento',
        'pasos_en_observacion',
        'hallazgos',
        'analisis_actividad',
        'plan_accion',
        'puntaje_opt_calculado',
        'clasificacion_opt',
        'actividad_detenida',
        'motivo_detencion',
        'cerrado_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ejecucion' => 'date',
            'pasos_observados' => 'array',
            'q1_procedimiento_disponible' => 'boolean',
            'q2_usa_epp_correctamente' => 'boolean',
            'q3_identifica_peligros_riesgos' => 'boolean',
            'q4_herramientas_disponibles' => 'boolean',
            'q5_area_limpia_ordenada' => 'boolean',
            'q6_aplica_controles' => 'boolean',
            'q7_procedimiento_actualizado' => 'boolean',
            'q8_procedimiento_facil_entendimiento' => 'boolean',
            'q9_procedimiento_divulgado' => 'boolean',
            'q10_personal_capacitado_certificado' => 'boolean',
            'q11_personal_mostro_habilidad' => 'boolean',
            'pasos_segun_procedimiento' => 'integer',
            'pasos_en_observacion' => 'integer',
            'analisis_actividad' => AnalisisActividad::class,
            'puntaje_opt_calculado' => 'float',
            'clasificacion_opt' => ClasificacionOpt::class,
            'actividad_detenida' => 'boolean',
            'cerrado_at' => 'datetime',
        ];
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function responsableArea(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_area_id');
    }

    public function observador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observador_id');
    }

    public function acompanante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acompanante_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inspeccionGerencial(): HasOne
    {
        return $this->hasOne(InspeccionGerencial::class);
    }

    public function reglasSalvaVidas(): HasMany
    {
        return $this->hasMany(InspeccionGerencialRegla::class);
    }

    public function accionesInspeccion(): HasMany
    {
        return $this->hasMany(InspeccionGerencialAccion::class);
    }

    public function estaCerrado(): bool
    {
        return $this->cerrado_at !== null;
    }

    public function esInspeccionGerencial(): bool
    {
        return $this->tipo_verificacion === 'inspeccion_gerencial_caminar_planta';
    }

    /**
     * Sub-total del checklist: cantidad de preguntas 1-11 marcadas "Sí" × 6.37%.
     * Retorna null si ninguna pregunta ha sido respondida aún.
     */
    public function subTotalChecklist(): ?float
    {
        if (collect(self::PREGUNTAS_CHECKLIST)->every(fn (string $q) => $this->{$q} === null)) {
            return null;
        }

        $marcadasSi = collect(self::PREGUNTAS_CHECKLIST)->filter(fn (string $q) => $this->{$q} === true)->count();

        return round($marcadasSi * self::VALOR_PREGUNTA, 2);
    }

    /**
     * Pregunta 12: ¿coinciden los pasos del procedimiento con los observados?
     * Retorna null si no se han diligenciado ambos conteos.
     */
    public function coincidenPasos(): ?bool
    {
        if ($this->pasos_segun_procedimiento === null || $this->pasos_en_observacion === null) {
            return null;
        }

        return $this->pasos_segun_procedimiento === $this->pasos_en_observacion;
    }

    /**
     * Total % Cumplimiento = Sub-Total (checklist) + 30 (si coinciden los pasos) o 0.
     * Retorna null si falta información para calcularlo.
     */
    public function calcularPuntajeOpt(): ?float
    {
        $subTotal = $this->subTotalChecklist();
        $coinciden = $this->coincidenPasos();

        if ($subTotal === null || $coinciden === null) {
            return null;
        }

        $puntajePregunta12 = $coinciden ? self::VALOR_PREGUNTA_12 : 0;

        return round($subTotal + $puntajePregunta12, 2);
    }
}
