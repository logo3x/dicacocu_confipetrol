<?php

namespace App\Models\Do;

use Database\Factories\Do\CampoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campo extends Model
{
    /** @use HasFactory<CampoFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'do_campos';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /** @return BelongsTo<Contrato, $this> */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /** @return HasMany<ProcedimientoDo, $this> */
    public function procedimientos(): HasMany
    {
        return $this->hasMany(ProcedimientoDo::class, 'campo_id');
    }

    /** @param  Builder<$this>  $query */
    public function scopeActivos(Builder $query): void
    {
        $query->where('activo', true);
    }
}
