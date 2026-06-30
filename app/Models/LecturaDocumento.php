<?php

namespace App\Models;

use Database\Factories\LecturaDocumentoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturaDocumento extends Model
{
    /** @use HasFactory<LecturaDocumentoFactory> */
    use HasFactory;

    protected $table = 'lectura_documentos';

    protected $fillable = [
        'documento_id',
        'user_id',
        'leido_at',
        'confirmado',
        'confirmado_at',
        'progreso_pct',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'leido_at' => 'datetime',
            'confirmado_at' => 'datetime',
            'confirmado' => 'boolean',
            'progreso_pct' => 'integer',
        ];
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function estaConfirmado(): bool
    {
        return $this->confirmado && $this->confirmado_at !== null;
    }
}
