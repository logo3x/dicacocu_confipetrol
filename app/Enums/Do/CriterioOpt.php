<?php

namespace App\Enums\Do;

/**
 * Criterio de aprobación cualitativo según el puntaje OPT del formato F-14.
 *
 * Fórmula original de la Matriz DO:
 * IFS(AG=0%,"SIN DATOS", AG<=59%,"DEFICIENTE", AG<=79%,"REGULAR", AG<=94%,"BUENO", AG>=95%,"EXCELENTE")
 */
enum CriterioOpt: string
{
    case SinDatos = 'sin_datos';
    case Deficiente = 'deficiente';
    case Regular = 'regular';
    case Bueno = 'bueno';
    case Excelente = 'excelente';

    public static function desdePuntaje(?float $puntaje): self
    {
        return match (true) {
            $puntaje === null, $puntaje <= 0 => self::SinDatos,
            $puntaje <= 59 => self::Deficiente,
            $puntaje <= 79 => self::Regular,
            $puntaje <= 94 => self::Bueno,
            default => self::Excelente,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::SinDatos => 'Sin datos',
            self::Deficiente => 'Deficiente',
            self::Regular => 'Regular',
            self::Bueno => 'Bueno',
            self::Excelente => 'Excelente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SinDatos => 'gray',
            self::Deficiente => 'danger',
            self::Regular => 'orange',
            self::Bueno => 'warning',
            self::Excelente => 'success',
        };
    }

    public function requierePlanDeAccion(): bool
    {
        return in_array($this, [self::Deficiente, self::Regular, self::Bueno], true);
    }
}
