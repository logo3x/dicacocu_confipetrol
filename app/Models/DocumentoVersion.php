<?php

namespace App\Models;

use Database\Factories\DocumentoVersionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoVersion extends Model
{
    /** @use HasFactory<DocumentoVersionFactory> */
    use HasFactory;

    protected $table = 'documento_versiones';

    protected $fillable = [
        'documento_id',
        'version',
        'cambios',
        'estado',
        'created_by',
        'revisado_por',
        'aprobado_por',
        'revisado_at',
        'aprobado_at',
        'motivo_rechazo',
        'archivo_path',
        'archivo_nombre',
        'archivo_size',
        'archivo_mime',
        'contenido_ocr',
    ];

    protected function casts(): array
    {
        return [
            'version'      => 'integer',
            'revisado_at'  => 'datetime',
            'aprobado_at'  => 'datetime',
            'archivo_size' => 'integer',
        ];
    }

    public function documento(): BelongsTo
    {
        return $this->belongsTo(Documento::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function aprobadorUsuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
