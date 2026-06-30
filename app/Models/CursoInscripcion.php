<?php

namespace App\Models;

use Database\Factories\CursoInscripcionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursoInscripcion extends Model
{
    /** @use HasFactory<CursoInscripcionFactory> */
    use HasFactory;

    protected $table = 'curso_inscripciones';

    protected $fillable = [
        'curso_id',
        'user_id',
        'estado',
        'nota',
        'respuestas',
        'completado_at',
        'certificado_at',
    ];

    protected function casts(): array
    {
        return [
            'respuestas' => 'array',
            'completado_at' => 'datetime',
            'certificado_at' => 'datetime',
            'nota' => 'integer',
        ];
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(CursoCumplimiento::class, 'curso_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estaAprobado(): bool
    {
        return $this->estado === 'aprobado';
    }

    public function tieneCertificado(): bool
    {
        return $this->certificado_at !== null;
    }
}
