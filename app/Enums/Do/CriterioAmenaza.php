<?php

namespace App\Enums\Do;

/**
 * Criterios de valoración de amenaza de la Matriz Integral de Disciplina Operativa.
 * Los pesos suman 100 y provienen de la fila 7 de la hoja DO del formato HSEQ-GCA1-F-17.
 */
enum CriterioAmenaza: string
{
    case RiesgoCritico = 'amenaza_riesgo_critico';
    case EquiposCriticos = 'amenaza_equipos_criticos';
    case ImpactoAmbiental = 'amenaza_impacto_ambiental';
    case Antecedentes = 'amenaza_antecedentes';
    case AfectaServicio = 'amenaza_afecta_servicio';
    case NoRutinaria = 'amenaza_no_rutinaria';

    public function peso(): int
    {
        return match ($this) {
            self::RiesgoCritico => 30,
            self::Antecedentes => 20,
            self::EquiposCriticos, self::ImpactoAmbiental => 15,
            self::AfectaServicio, self::NoRutinaria => 10,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::RiesgoCritico => 'Actividad clasificada como riesgo crítico según análisis de riesgos o HAZOP',
            self::EquiposCriticos => 'Actividad involucra equipos críticos para la seguridad de procesos',
            self::ImpactoAmbiental => 'La ejecución de la actividad puede generar un impacto ambiental significativo',
            self::Antecedentes => 'La actividad presenta antecedentes de accidentalidad en la organización o industria',
            self::AfectaServicio => 'Una ejecución inadecuada de la actividad puede afectar el desempeño del servicio',
            self::NoRutinaria => 'Actividad es no rutinaria',
        };
    }
}
