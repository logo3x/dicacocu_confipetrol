<?php

namespace App\Models\Do;

use App\Models\User;
use Database\Factories\Do\EvaluacionAccionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionAccion extends Model
{
    /** @use HasFactory<EvaluacionAccionFactory> */
    use HasFactory;

    protected $table = 'do_evaluacion_acciones';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'fecha_cierre' => 'date',
            'cerrada_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<EvaluacionF14, $this> */
    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(EvaluacionF14::class, 'evaluacion_id');
    }

    /** @return BelongsTo<User, $this> */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
