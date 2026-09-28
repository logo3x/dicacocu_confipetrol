<?php

namespace App\Enums\Do;

/**
 * Prioridad derivada del puntaje de amenaza (0-100) de la Matriz Integral DO.
 *
 * Escala directa: a mayor puntaje, mayor riesgo y mayor prioridad.
 * Fórmula original: IFS(M<=59,"BAJO", M<=79,"MEDIO", M>=80,"ALTO")
 */
enum PrioridadDo: string
{
    case Bajo = 'bajo';
    case Medio = 'medio';
    case Alto = 'alto';

    public static function desdePuntaje(int $puntaje): self
    {
        return match (true) {
            $puntaje <= 59 => self::Bajo,
            $puntaje <= 79 => self::Medio,
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

    /**
     * Tiempo máximo de estandarización según prioridad.
     * IFS(N="ALTO","Máx 1 mes", N="MEDIO","Máx 2 meses", N="BAJO","Máx 4 meses")
     */
    public function plazoEstandarizacionMeses(): int
    {
        return match ($this) {
            self::Alto => 1,
            self::Medio => 2,
            self::Bajo => 4,
        };
    }

    /**
     * Frecuencia de verificación de DO según prioridad.
     * IFS(N="ALTO","Cada 6 meses", N="MEDIO","Cada 9 meses", N="BAJO","Cada 12 meses")
     */
    public function frecuenciaVerificacionMeses(): int
    {
        return match ($this) {
            self::Alto => 6,
            self::Medio => 9,
            self::Bajo => 12,
        };
    }

    public function etiquetaPlazo(): string
    {
        return 'Máx '.$this->plazoEstandarizacionMeses().' '.($this->plazoEstandarizacionMeses() === 1 ? 'mes' : 'meses');
    }

    public function etiquetaFrecuencia(): string
    {
        return 'Cada '.$this->frecuenciaVerificacionMeses().' meses';
    }
}
