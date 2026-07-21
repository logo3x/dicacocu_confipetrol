<?php

namespace App\Models;

use App\Enums\EstadoSocializacion;
use App\Enums\PrioridadAmenaza;
use Database\Factories\ActividadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actividad extends Model
{
    /** @use HasFactory<ActividadFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'actividades';

    protected $fillable = [
        'nombre',
        'contrato',
        'campo',
        'descripcion',
        'personal_expuesto',
        'valoracion_amenaza',
        'prioridad_amenaza',
        'fecha_identificacion',
        'fecha_limite_estandarizacion',
        'fecha_limite_verificacion',
        'documento_id',
        'responsable_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'personal_expuesto' => 'integer',
            'valoracion_amenaza' => 'integer',
            'prioridad_amenaza' => PrioridadAmenaza::class,
            'fecha_identificacion' => 'date',
            'fecha_limite_estandarizacion' => 'date',
            'fecha_limite_verificacion' => 'date',
        ];
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acompanamientos(): HasMany
    {
        return $this->hasMany(AcompanamientoVerificacion::class);
    }

    public function personalExpuestoNominal(): HasMany
    {
        return $this->hasMany(ActividadPersonalExpuesto::class);
    }

    public function estaEstandarizada(): bool
    {
        return $this->documento_id !== null;
    }

    public function estandarizacionVencida(): bool
    {
        return ! $this->estaEstandarizada()
            && $this->fecha_limite_estandarizacion !== null
            && $this->fecha_limite_estandarizacion->isPast();
    }

    public function coberturaSocializacion(): float
    {
        $total = $this->personalExpuestoNominal()->count();

        if ($total === 0) {
            return 0.0;
        }

        $vigentes = $this->personalExpuestoNominal()
            ->get()
            ->filter(fn (ActividadPersonalExpuesto $p) => $p->estado() === EstadoSocializacion::Vigente)
            ->count();

        return round(($vigentes / $total) * 100, 1);
    }
}
