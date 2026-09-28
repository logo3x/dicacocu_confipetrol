<?php

namespace App\Support\Do;

/**
 * Indicadores de Disciplina Operativa de la hoja INDICADORES del formato HSEQ-GCA1-F-17.
 * Cada indicador pesa 25% en el porcentaje global de cumplimiento DO.
 */
final readonly class IndicadoresDo
{
    public const META_DISPONIBILIDAD = 90.0;

    public const META_CALIDAD = 90.0;

    public const META_COMUNICACION = 80.0;

    public const META_CUMPLIMIENTO = 90.0;

    public const PESO = 0.25;

    public function __construct(
        public float $disponibilidad,
        public float $calidad,
        public float $comunicacion,
        public float $cumplimiento,
        public int $totalProcedimientos = 0,
        public int $estandarizados = 0,
    ) {}

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

    /**
     * Porcentaje global de cumplimiento DO.
     * (M8*H8)+(M10*H10)+(M12*H12)+(M13*H13) con H = 0,25 en las cuatro filas.
     */
    public function global(): float
    {
        return round(
            ($this->disponibilidad + $this->calidad + $this->comunicacion + $this->cumplimiento) * self::PESO,
            2,
        );
    }
}
