<?php

namespace App\Services\Do;

use App\Enums\Do\CriterioAmenaza;
use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;

/**
 * Réplica de las fórmulas de los formatos HSEQ-GCA1-F-17 (Matriz Integral DO)
 * y HSEQ-GCA1-F-14 (Acompañamiento y Verificación de Actividades).
 */
class CalculadoraDo
{
    /** Valor porcentual de cada una de las 11 preguntas del checklist F-14. */
    public const VALOR_PREGUNTA = 6.37;

    /** Valor de la pregunta 12 (coincidencia de pasos). */
    public const VALOR_PREGUNTA_12 = 30;

    public const TOTAL_PREGUNTAS_CHECKLIST = 11;

    /**
     * Puntaje de amenaza: suma ponderada de los criterios marcados.
     *
     * @param  array<string, bool>  $criterios  indexado por el value de CriterioAmenaza
     */
    public static function puntajePrioridad(array $criterios): int
    {
        $total = 0;

        foreach (CriterioAmenaza::cases() as $criterio) {
            if (! empty($criterios[$criterio->value])) {
                $total += $criterio->peso();
            }
        }

        return $total;
    }

    /**
     * Cobertura de socialización: personas socializadas / personas involucradas.
     * IFERROR(Y/F, 0) — devuelve 0 cuando no hay personal involucrado.
     */
    public static function coberturaSocializacion(?int $socializadas, ?int $involucradas): float
    {
        if (! $involucradas || $involucradas <= 0) {
            return 0.0;
        }

        return round(($socializadas ?? 0) / $involucradas * 100, 2);
    }

    /**
     * Subtotal del checklist F-14: 6,37% por cada respuesta afirmativa (máx 70,07%).
     *
     * @param  array<int, bool>  $respuestas
     */
    public static function subtotalChecklist(array $respuestas): float
    {
        $afirmativas = count(array_filter($respuestas));

        return round($afirmativas * self::VALOR_PREGUNTA, 2);
    }

    /**
     * Puntaje OPT total: subtotal del checklist más 30% si los pasos coinciden.
     *
     * @param  array<int, bool>  $respuestas
     */
    public static function puntajeOpt(array $respuestas, ?int $pasosSegunProcedimiento, ?int $pasosEnObservacion): float
    {
        $subtotal = self::subtotalChecklist($respuestas);

        if (self::pasosCoinciden($pasosSegunProcedimiento, $pasosEnObservacion)) {
            $subtotal += self::VALOR_PREGUNTA_12;
        }

        return round($subtotal, 2);
    }

    public static function pasosCoinciden(?int $segunProcedimiento, ?int $enObservacion): bool
    {
        if ($segunProcedimiento === null || $enObservacion === null) {
            return false;
        }

        return $segunProcedimiento === $enObservacion;
    }

    public static function prioridad(int $puntaje): PrioridadDo
    {
        return PrioridadDo::desdePuntaje($puntaje);
    }

    public static function criterioOpt(?float $puntaje): CriterioOpt
    {
        return CriterioOpt::desdePuntaje($puntaje);
    }
}
