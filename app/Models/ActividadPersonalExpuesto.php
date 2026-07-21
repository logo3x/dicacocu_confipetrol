<?php

namespace App\Models;

use App\Enums\EstadoSocializacion;
use Database\Factories\ActividadPersonalExpuestoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ActividadPersonalExpuesto extends Model
{
    /** @use HasFactory<ActividadPersonalExpuestoFactory> */
    use HasFactory;

    protected $table = 'actividad_personal_expuesto';

    protected $fillable = [
        'actividad_id',
        'user_id',
        'fecha_socializacion',
        'fecha_vencimiento',
        'socializado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha_socializacion' => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function socializadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'socializado_por');
    }

    public function estado(): EstadoSocializacion
    {
        if ($this->fecha_socializacion === null) {
            return EstadoSocializacion::Pendiente;
        }

        if ($this->fecha_vencimiento !== null && $this->fecha_vencimiento->isPast()) {
            return EstadoSocializacion::Vencida;
        }

        return EstadoSocializacion::Vigente;
    }

    public function socializar(User $socializador, ?Carbon $fecha = null): void
    {
        $fecha ??= now();

        $this->update([
            'fecha_socializacion' => $fecha->toDateString(),
            'fecha_vencimiento' => $fecha->copy()->addYear()->toDateString(),
            'socializado_por' => $socializador->id,
        ]);
    }
}
