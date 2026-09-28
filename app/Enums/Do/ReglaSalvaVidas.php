<?php

namespace App\Enums\Do;

/**
 * Las 12 Reglas que Salvan Vidas de Confipetrol, evaluadas en la Parte 2
 * (Inspección Gerencial "Caminar la Planta") del formato HSEQ-GCA1-F-14.
 */
enum ReglaSalvaVidas: int
{
    case UsoEpp = 1;
    case PermisosTrabajo = 2;
    case TrabajoAlturas = 3;
    case AislamientoBloqueo = 4;
    case EspaciosConfinados = 5;
    case TrabajosCaliente = 6;
    case RiesgosMecanicos = 7;
    case ManejoCargas = 8;
    case SinCelularAlcoholDrogas = 9;
    case OperacionVehiculos = 10;
    case SustanciasQuimicas = 11;
    case SuspensionTareasInseguras = 12;

    public function label(): string
    {
        return match ($this) {
            self::UsoEpp => 'Uso de EPPs',
            self::PermisosTrabajo => 'Permisos de trabajo y Análisis de Riesgos',
            self::TrabajoAlturas => 'Trabajo en Alturas',
            self::AislamientoBloqueo => 'Aislamiento, Bloqueo y Tarjeteo de Energías',
            self::EspaciosConfinados => 'Trabajo en espacios confinados',
            self::TrabajosCaliente => 'Prevención de riesgos con trabajos en caliente',
            self::RiesgosMecanicos => 'Prevención de riesgos mecánicos',
            self::ManejoCargas => 'Manejo seguro de cargas',
            self::SinCelularAlcoholDrogas => 'Sin celular, cero alcohol y/o drogas al trabajar',
            self::OperacionVehiculos => 'Operación segura de vehículos y equipos',
            self::SustanciasQuimicas => 'Trabajo seguro con sustancias químicas',
            self::SuspensionTareasInseguras => 'Suspensión de tareas inseguras',
        };
    }
}
