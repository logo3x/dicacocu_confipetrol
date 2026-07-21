<?php

namespace App\Support;

/**
 * Resultado del cálculo de los 4 indicadores del ciclo DICACOCO
 * (Disponibilidad, Calidad, Comunicación, Cumplimiento) de Disciplina Operativa.
 */
final readonly class IndicadoresDicacoco
{
    public function __construct(
        public float $disponibilidad,
        public float $calidad,
        public float $comunicacion,
        public float $cumplimiento,
    ) {}

    public const META_DISPONIBILIDAD = 90.0;

    public const META_CALIDAD = 90.0;

    public const META_COMUNICACION = 80.0;

    public const META_CUMPLIMIENTO = 90.0;

    public function cumpleDisponibilidad(): bool
    {
        return $this->disponibilidad >= self::META_DISPONIBILIDAD;
    }

    public function cumpleCalidad(): bool
    {
        return $this->calidad >= self::META_CALIDAD;
    }

    public function cumpleComunicacion(): bool
    {
        return $this->comunicacion >= self::META_COMUNICACION;
    }

    public function cumpleCumplimiento(): bool
    {
        return $this->cumplimiento >= self::META_CUMPLIMIENTO;
    }
}
