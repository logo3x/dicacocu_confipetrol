<?php

namespace App\Enums;

/**
 * Clasificación del puntaje OPT resultante del formato de Acompañamiento
 * y Verificación de Actividades (HSEQ-GCA1-F-14).
 */
enum ClasificacionOpt: string
{
    case Excelente = 'excelente';
    case Bueno = 'bueno';
    case Regular = 'regular';
    case Deficiente = 'deficiente';

    public static function desdePuntaje(float $puntaje): self
    {
        return match (true) {
            $puntaje >= 95 => self::Excelente,
            $puntaje >= 80 => self::Bueno,
            $puntaje >= 60 => self::Regular,
            default => self::Deficiente,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Excelente => 'Excelente',
            self::Bueno => 'Bueno',
            self::Regular => 'Regular',
            self::Deficiente => 'Deficiente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Excelente => 'success',
            self::Bueno => 'warning',
            self::Regular => 'orange',
            self::Deficiente => 'danger',
        };
    }

    public function estado(): string
    {
        return match ($this) {
            self::Excelente => 'Aprobado. Sin plan de acción; se promueve el reconocimiento del equipo.',
            self::Bueno => 'Aprobado con observaciones. Requiere documentar acciones preventivas o de mejora.',
            self::Regular => 'Condicional. Se identificaron hallazgos importantes que requieren plan de acción correctivo.',
            self::Deficiente => 'No aprobado. Alerta operativa: puede implicar detención de la actividad, reentrenamiento de personal y nueva verificación.',
        };
    }

    public function requierePlanDeAccion(): bool
    {
        return $this !== self::Excelente;
    }
}
