<?php

namespace App\Enums;

/**
 * Prioridad de una actividad derivada de su valoración de amenaza (0-100).
 *
 * Nota: la escala es inversa — un puntaje de amenaza alto implica prioridad baja
 * (la actividad está bien controlada), y un puntaje bajo implica prioridad alta
 * (requiere atención urgente).
 */
enum PrioridadAmenaza: string
{
    case Bajo = 'bajo';
    case Medio = 'medio';
    case Alto = 'alto';

    public static function desdeValoracion(int $valoracion): self
    {
        return match (true) {
            $valoracion >= 80 => self::Bajo,
            $valoracion >= 60 => self::Medio,
            default => self::Alto,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Bajo => 'Bajo',
            self::Medio => 'Medio',
            self::Alto => 'Alto',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Bajo => 'success',
            self::Medio => 'warning',
            self::Alto => 'danger',
        };
    }

    public function plazoEstandarizacionMeses(): int
    {
        return match ($this) {
            self::Bajo => 4,
            self::Medio => 2,
            self::Alto => 1,
        };
    }

    public function frecuenciaVerificacionMeses(): int
    {
        return match ($this) {
            self::Bajo => 12,
            self::Medio => 6,
            self::Alto => 3,
        };
    }
}
