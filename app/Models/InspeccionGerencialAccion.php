<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspeccionGerencialAccion extends Model
{
    protected $table = 'inspeccion_gerencial_acciones';

    protected $fillable = [
        'inspeccion_gerencial_id',
        'acompanamiento_verificacion_id',
        'accion',
        'responsable_id',
        'fecha_cierre',
    ];

    protected function casts(): array
    {
        return [
            'fecha_cierre' => 'date',
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

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
