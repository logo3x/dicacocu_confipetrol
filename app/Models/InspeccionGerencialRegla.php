<?php

namespace App\Models;

use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspeccionGerencialRegla extends Model
{
    protected $fillable = [
        'inspeccion_gerencial_id',
        'acompanamiento_verificacion_id',
        'numero_regla',
        'cumple',
    ];

    protected function casts(): array
    {
        return [
            'numero_regla' => ReglaSalvaVidas::class,
            'cumple' => CumpleRegla::class,
        ];
    }

    public function inspeccionGerencial(): BelongsTo
    {
        return $this->belongsTo(InspeccionGerencial::class);
    }

    public function acompanamientoVerificacion(): BelongsTo
    {
        return $this->belongsTo(AcompanamientoVerificacion::class);
    }
}
