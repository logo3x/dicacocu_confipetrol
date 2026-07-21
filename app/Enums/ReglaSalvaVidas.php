<?php

namespace App\Enums;

/**
 * Las 12 Reglas que Salvan Vidas de Confipetrol, verificadas en la Parte 2
 * (Inspección Gerencial Caminar la Planta) del formato HSEQ-GCA1-F-14.
 */
enum ReglaSalvaVidas: int
{
    case UsoDeEpps = 1;
    case PermisosTrabajoAnalisisRiesgos = 2;
    case TrabajoEnAlturas = 3;
    case AislamientoBloqueoTarjeteoEnergias = 4;
    case TrabajoEnEspaciosConfinados = 5;
    case PrevencionRiesgosTrabajosCaliente = 6;
    case PrevencionRiesgosMecanicos = 7;
    case ManejoSeguroDeCargas = 8;
    case SinCelularAlcoholDrogas = 9;
    case OperacionSeguraVehiculosEquipos = 10;
    case TrabajoSeguroSustanciasQuimicas = 11;
    case SuspensionTareasInseguras = 12;

    public function label(): string
    {
        return match ($this) {
            self::UsoDeEpps => 'Uso de EPPs',
            self::PermisosTrabajoAnalisisRiesgos => 'Permisos de trabajo y Análisis de Riesgos',
            self::TrabajoEnAlturas => 'Trabajo en Alturas',
            self::AislamientoBloqueoTarjeteoEnergias => 'Aislamiento, Bloqueo y Tarjeteo de Energías',
            self::TrabajoEnEspaciosConfinados => 'Trabajo en espacios confinados',
            self::PrevencionRiesgosTrabajosCaliente => 'Prevención de riesgos con trabajos en caliente',
            self::PrevencionRiesgosMecanicos => 'Prevención de riesgos mecánicos',
            self::ManejoSeguroDeCargas => 'Manejo Seguro de cargas',
            self::SinCelularAlcoholDrogas => 'Sin celular, cero alcohol y/o drogas al trabajar',
            self::OperacionSeguraVehiculosEquipos => 'Operación segura de vehículos y equipos',
            self::TrabajoSeguroSustanciasQuimicas => 'Trabajo seguro con sustancias químicas',
            self::SuspensionTareasInseguras => 'Suspensión de tareas inseguras',
        };
    }
}
