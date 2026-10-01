<?php

namespace App\Models;

use Database\Factories\CarpetaFactory;
use Illuminate\Database\Eloquent\Builder;
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
            'orden' => 'integer',
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

    /** Carpetas sin padre: el primer nivel del árbol. */
    public function scopeRaices(Builder $consulta): Builder
    {
        return $consulta->whereNull('parent_id');
    }

    /**
     * Cuántos niveles hay por encima de esta carpeta. Una raíz está en 0.
     * Se detiene a los 10 niveles por si un dato corrupto encadena un ciclo.
     */
    public function profundidad(): int
    {
        $niveles = 0;
        $actual = $this->parent;

        while ($actual && $niveles < 10) {
            $niveles++;
            $actual = $actual->parent;
        }

        return $niveles;
    }

    /** Ruta legible desde la raíz, por ejemplo "HSEQ / Procedimientos". */
    public function rutaCompleta(string $separador = ' / '): string
    {
        $nombres = [$this->nombre];
        $actual = $this->parent;
        $vueltas = 0;

        while ($actual && $vueltas < 10) {
            array_unshift($nombres, $actual->nombre);
            $actual = $actual->parent;
            $vueltas++;
        }

        return implode($separador, $nombres);
    }
}
