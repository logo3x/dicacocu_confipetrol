<?php

namespace App\Enums;

/**
 * Estado de socialización de un procedimiento para una persona expuesta a una actividad.
 * Se deriva de fecha_socializacion/fecha_vencimiento, no se almacena directamente.
 */
enum EstadoSocializacion: string
{
    case Pendiente = 'pendiente';
    case Vigente = 'vigente';
    case Vencida = 'vencida';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Vigente => 'Vigente',
            self::Vencida => 'Vencida',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pendiente => 'gray',
            self::Vigente => 'success',
            self::Vencida => 'danger',
        };
    }
}
