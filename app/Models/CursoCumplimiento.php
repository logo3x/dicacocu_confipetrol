<?php

namespace App\Models;

use Database\Factories\CursoCumplimientoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CursoCumplimiento extends Model
{
    /** @use HasFactory<CursoCumplimientoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'curso_cumplimientos';

    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'created_by',
        'documentos_ids',
        'preguntas',
        'nota_aprobacion',
        'fecha_limite',
        'certificado_activo',
    ];

    protected function casts(): array
    {
        return [
            'documentos_ids' => 'array',
            'preguntas' => 'array',
            'fecha_limite' => 'date',
            'certificado_activo' => 'boolean',
            'nota_aprobacion' => 'integer',
        ];
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(CursoInscripcion::class, 'curso_id');
    }

    public function documentos()
    {
        if (empty($this->documentos_ids)) {
            return Documento::whereNull('id');
        }

        return Documento::whereIn('id', $this->documentos_ids);
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    public function getInscritoCount(): int
    {
        return $this->inscripciones()->count();
    }

    public function getAprobadoCount(): int
    {
        return $this->inscripciones()->where('estado', 'aprobado')->count();
    }
}
