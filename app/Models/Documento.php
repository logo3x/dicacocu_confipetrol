<?php

namespace App\Models;

use App\Models\Do\ProcedimientoDo;
use Database\Factories\DocumentoFactory;
use Illuminate\Database\Eloquent\Builder;
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
            'fecha_emision' => 'date',
            'fecha_revision' => 'date',
            'fecha_vencimiento' => 'date',
            'tags' => 'array',
            'metadatos' => 'array',
            'requiere_firma' => 'boolean',
            'confidencial' => 'boolean',
            'version_actual' => 'integer',
            'visitas' => 'integer',
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

    /**
     * Procedimientos de la matriz DICACOCU que apuntan a este documento.
     *
     * @return HasMany<ProcedimientoDo, $this>
     */
    public function procedimientosDo(): HasMany
    {
        return $this->hasMany(ProcedimientoDo::class, 'documento_id');
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

    public function lecturas(): HasMany
    {
        return $this->hasMany(LecturaDocumento::class);
    }

    public function lecturaDeUsuario(int $userId): ?LecturaDocumento
    {
        return $this->lecturas()->where('user_id', $userId)->first();
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

    /**
     * Documentos que esta persona puede ver en un listado o búsqueda.
     *
     * Replica el criterio de DocumentoPolicy::view() a nivel de consulta: sin
     * esto un confidencial no se puede abrir, pero su título, código y
     * descripción sí aparecen en los listados.
     *
     * @param  Builder<Documento>  $consulta
     * @return Builder<Documento>
     */
    public function scopeVisiblePara(Builder $consulta, ?User $usuario): Builder
    {
        if ($usuario === null) {
            return $consulta->where('confidencial', false);
        }

        if ($usuario->can('ver documentos confidenciales')) {
            return $consulta;
        }

        return $consulta->where(fn (Builder $q) => $q
            ->where('confidencial', false)
            ->orWhere(fn (Builder $propios) => $propios
                ->where('confidencial', true)
                ->where(fn (Builder $suyo) => $suyo
                    ->where('created_by', $usuario->getKey())
                    ->orWhere('responsable_id', $usuario->getKey())
                    ->orWhere('aprobador_id', $usuario->getKey()))));
    }
}
