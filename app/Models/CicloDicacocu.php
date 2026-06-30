<?php

namespace App\Models;

use Database\Factories\CicloDicacocuFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CicloDicacocu extends Model
{
    /** @use HasFactory<CicloDicacocuFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'ciclos_dicacocu';

    protected $fillable = [
        'nombre',
        'codigo',
        'fase',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'responsable_id',
        'documentos_ids',
        'progreso',
        'indicadores',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio'   => 'date',
            'fecha_fin'      => 'date',
            'documentos_ids' => 'array',
            'indicadores'    => 'array',
            'progreso'       => 'integer',
        ];
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
