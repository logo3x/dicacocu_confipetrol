<?php

namespace App\Enums;

/**
 * Conclusión del análisis de la actividad observada, sección final
 * de la Parte 1 del formato HSEQ-GCA1-F-14.
 */
enum AnalisisActividad: string
{
    case SigueCorrectamente = 'sigue_correctamente';
    case SigueDebeMejorar = 'sigue_debe_mejorar';
    case MejorarProcedimiento = 'mejorar_procedimiento';
    case DifusionReentrenamiento = 'difusion_reentrenamiento';
    case FortalecerDo = 'fortalecer_do';
    case SuspensionTareas = 'suspension_tareas';

    public function label(): string
    {
        return match ($this) {
            self::SigueCorrectamente => 'Sigue correctamente el procedimiento de la actividad',
            self::SigueDebeMejorar => 'Sigue el procedimiento de la actividad pero debe mejorar',
            self::MejorarProcedimiento => 'Se debe mejorar el Procedimiento',
            self::DifusionReentrenamiento => 'Se sugiere realizar difusión o re-entrenamiento',
            self::FortalecerDo => 'Se debe fortalecer la Disciplina Operativa',
            self::SuspensionTareas => 'Se requiere aplicar suspensión de tareas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SigueCorrectamente => 'success',
            self::SigueDebeMejorar => 'warning',
            self::MejorarProcedimiento => 'orange',
            self::DifusionReentrenamiento => 'orange',
            self::FortalecerDo => 'danger',
            self::SuspensionTareas => 'danger',
        };
    }
}
