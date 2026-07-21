<?php

namespace App\Models;

use App\Enums\EstadoCompromiso;
use Database\Factories\CompromisoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compromiso extends Model
{
    /** @use HasFactory<CompromisoFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'contrato',
        'fecha_limite',
        'responsable_id',
        'rol_responsable',
        'cumplido_at',
        'cumplido_por',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_limite' => 'date',
            'cumplido_at' => 'datetime',
        ];
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function cumplidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cumplido_por');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function estado(): EstadoCompromiso
    {
        if ($this->cumplido_at !== null) {
            return EstadoCompromiso::Cumplido;
        }

        if ($this->fecha_limite->isPast()) {
            return EstadoCompromiso::Vencido;
        }

        return EstadoCompromiso::Pendiente;
    }

    public function marcarCumplido(User $usuario): void
    {
        $this->update([
            'cumplido_at' => now(),
            'cumplido_por' => $usuario->id,
        ]);
    }
}
