<?php

namespace App\Models\Do;

use Database\Factories\Do\ContratoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    /** @use HasFactory<ContratoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'do_contratos';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /** @return HasMany<Campo, $this> */
    public function campos(): HasMany
    {
        return $this->hasMany(Campo::class, 'contrato_id');
    }

    /** @return HasMany<ProcedimientoDo, $this> */
    public function procedimientos(): HasMany
    {
        return $this->hasMany(ProcedimientoDo::class, 'contrato_id');
    }

    /** @param  Builder<$this>  $query */
    public function scopeActivos(Builder $query): void
    {
        $query->where('activo', true);
    }
}
