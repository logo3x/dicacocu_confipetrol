<?php

namespace App\Models\Do;

use App\Enums\Do\CumpleRegla;
use App\Enums\Do\ReglaSalvaVidas;
use Database\Factories\Do\EvaluacionReglaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionRegla extends Model
{
    /** @use HasFactory<EvaluacionReglaFactory> */
    use HasFactory;

    protected $table = 'do_evaluacion_reglas';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'numero_regla' => ReglaSalvaVidas::class,
            'cumple' => CumpleRegla::class,
        ];
    }

    /** @return BelongsTo<EvaluacionF14, $this> */
    public function evaluacion(): BelongsTo
    {
        return $this->belongsTo(EvaluacionF14::class, 'evaluacion_id');
    }
}
