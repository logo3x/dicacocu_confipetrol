<?php

namespace App\Enums;

/**
 * Íconos disponibles para identificar una carpeta. Se ofrecen como lista
 * cerrada para no pedirle al usuario que escriba el nombre de un ícono.
 *
 * Los valores son Heroicons, que es el juego que ya trae Filament. Antes se
 * guardaban nombres de Font Awesome, una librería que el panel nunca cargó,
 * así que esos íconos no llegaban a verse.
 */
enum IconoCarpeta: string
{
    case Carpeta = 'heroicon-o-folder';
    case Documento = 'heroicon-o-document-text';
    case Procedimiento = 'heroicon-o-clipboard-document-list';
    case Seguridad = 'heroicon-o-shield-check';
    case Mantenimiento = 'heroicon-o-wrench-screwdriver';
    case Equipos = 'heroicon-o-cog-6-tooth';
    case Legal = 'heroicon-o-scale';
    case Calidad = 'heroicon-o-check-badge';
    case Emergencia = 'heroicon-o-exclamation-triangle';
    case Capacitacion = 'heroicon-o-academic-cap';
    case Ambiente = 'heroicon-o-globe-americas';
    case Personas = 'heroicon-o-users';

    public function label(): string
    {
        return match ($this) {
            self::Carpeta => 'Carpeta',
            self::Documento => 'Documento',
            self::Procedimiento => 'Procedimiento',
            self::Seguridad => 'Seguridad',
            self::Mantenimiento => 'Mantenimiento',
            self::Equipos => 'Equipos',
            self::Legal => 'Legal',
            self::Calidad => 'Calidad',
            self::Emergencia => 'Emergencia',
            self::Capacitacion => 'Capacitación',
            self::Ambiente => 'Ambiente',
            self::Personas => 'Personas',
        };
    }

    /**
     * Opciones para un Select.
     *
     * @return array<string, string>
     */
    public static function opciones(): array
    {
        $opciones = [];

        foreach (self::cases() as $icono) {
            $opciones[$icono->value] = $icono->label();
        }

        return $opciones;
    }

    /**
     * Tolera los valores de Font Awesome guardados antes de cerrar la lista,
     * para que las carpetas existentes sigan mostrando un ícono con sentido.
     */
    public static function desdeValor(?string $valor): self
    {
        if ($icono = self::tryFrom((string) $valor)) {
            return $icono;
        }

        return match ($valor) {
            'fa-file-lines' => self::Documento,
            'fa-clipboard-list' => self::Procedimiento,
            'fa-shield-halved' => self::Seguridad,
            'fa-wrench' => self::Mantenimiento,
            'fa-gears' => self::Equipos,
            'fa-gavel' => self::Legal,
            'fa-circle-check' => self::Calidad,
            'fa-triangle-exclamation' => self::Emergencia,
            'fa-graduation-cap' => self::Capacitacion,
            'fa-leaf' => self::Ambiente,
            'fa-users' => self::Personas,
            default => self::Carpeta,
        };
    }
}
