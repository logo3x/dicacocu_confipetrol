<?php

namespace App\Models;

use Database\Factories\DocumentoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Documento extends Model implements HasMedia
{
    /** @use HasFactory<DocumentoFactory> */
    use HasFactory, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $fillable = [
        'titulo',
        'codigo',
        'descripcion',
        'tipo_documento',
        'estado',
        'carpeta_id',
        'created_by',
        'responsable_id',
        'aprobador_id',
        'fase_dicacocu',
        'fecha_emision',
        'fecha_revision',
        'fecha_vencimiento',
        'version_actual',
        'tags',
        'metadatos',
        'requiere_firma',
        'confidencial',
        'visitas',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision'    => 'date',
            'fecha_revision'   => 'date',
            'fecha_vencimiento' => 'date',
            'tags'             => 'array',
            'metadatos'        => 'array',
            'requiere_firma'   => 'boolean',
            'confidencial'     => 'boolean',
            'version_actual'   => 'integer',
            'visitas'          => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Documento {$eventName}");
    }

    public function carpeta(): BelongsTo
    {
        return $this->belongsTo(Carpeta::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobador_id');
    }

    public function versiones(): HasMany
    {
        return $this->hasMany(DocumentoVersion::class);
    }

    public function versionActual(): BelongsTo
    {
        return $this->belongsTo(DocumentoVersion::class, 'version_actual', 'version')
            ->where('documento_id', $this->id);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('archivo_principal')->singleFile();
        $this->addMediaCollection('adjuntos');
    }

    public function estaVigente(): bool
    {
        return $this->estado === 'aprobado' || $this->estado === 'divulgado';
    }

    public function estaVencido(): bool
    {
        return $this->fecha_vencimiento !== null && $this->fecha_vencimiento->isPast();
    }
}
