<?php

namespace App\Models;

use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use Database\Factories\InspeccionGerencialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InspeccionGerencial extends Model
{
    /** @use HasFactory<InspeccionGerencialFactory> */
    use HasFactory;

    protected $table = 'inspecciones_gerenciales';

    protected $fillable = [
        'acompanamiento_verificacion_id',
        'hallazgos_positivos',
        'desvios_oportunidades_mejora',
    ];

    public function acompanamientoVerificacion(): BelongsTo
    {
        return $this->belongsTo(AcompanamientoVerificacion::class);
    }

    public function reglas(): HasMany
    {
        return $this->hasMany(InspeccionGerencialRegla::class);
    }

    public function acciones(): HasMany
    {
        return $this->hasMany(InspeccionGerencialAccion::class);
    }

    /**
     * Crea las 12 filas de reglas (sin respuesta aún) si todavía no existen.
     */
    public function inicializarReglas(): void
    {
        foreach (ReglaSalvaVidas::cases() as $regla) {
            $this->reglas()->firstOrCreate(
                ['numero_regla' => $regla->value],
                ['acompanamiento_verificacion_id' => $this->acompanamiento_verificacion_id],
            );
        }
    }

    public function contarIncumplimientos(): int
    {
        return $this->reglas()->where('cumple', CumpleRegla::No->value)->count();
    }
}
