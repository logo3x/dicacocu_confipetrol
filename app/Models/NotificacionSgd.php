<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificacionSgd extends Model
{
    protected $table = 'notificaciones_sgd';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'notificable_type',
        'notificable_id',
        'datos',
        'leido_at',
    ];

    protected function casts(): array
    {
        return [
            'datos'    => 'array',
            'leido_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notificable(): MorphTo
    {
        return $this->morphTo();
    }

    public function marcarLeida(): void
    {
        $this->update(['leido_at' => now()]);
    }

    public function estaLeida(): bool
    {
        return $this->leido_at !== null;
    }
}
