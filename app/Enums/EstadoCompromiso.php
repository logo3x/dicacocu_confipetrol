<?php

namespace App\Enums;

/**
 * Estado de un compromiso/hito de Disciplina Operativa.
 * Se deriva de fecha_limite/cumplido_at, no se almacena directamente.
 */
enum EstadoCompromiso: string
{
    case Pendiente = 'pendiente';
    case Cumplido = 'cumplido';
    case Vencido = 'vencido';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente',
            self::Cumplido => 'Cumplido',
            self::Vencido => 'Vencido',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pendiente => 'warning',
            self::Cumplido => 'success',
            self::Vencido => 'danger',
        };
    }
}
