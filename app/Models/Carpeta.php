<?php

namespace App\Models;

use Database\Factories\CarpetaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carpeta extends Model
{
    /** @use HasFactory<CarpetaFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'parent_id',
        'created_by',
        'color',
        'icono',
        'is_public',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'orden'     => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Carpeta::class, 'parent_id');
    }

    public function subcarpetas(): HasMany
    {
        return $this->hasMany(Carpeta::class, 'parent_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }
}
