<?php

namespace App\Enums;

use Illuminate\Support\Str;

/**
 * Roles del sistema y su nombre legible, para no mostrar al usuario el
 * identificador interno con guiones bajos.
 */
enum RolSistema: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case GestorDocumental = 'gestor_documental';
    case Operativo = 'operativo';
    case CalidadCorporativa = 'calidad_corporativa';
    case LiderOm = 'lider_om';
    case ResponsableHseq = 'responsable_hseq';
    case PersonalTecnico = 'personal_tecnico';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Superadministrador',
            self::Admin => 'Administrador',
            self::GestorDocumental => 'Gestor documental',
            self::Operativo => 'Operativo',
            self::CalidadCorporativa => 'Calidad corporativa',
            self::LiderOm => 'Líder O&M',
            self::ResponsableHseq => 'Responsable HSEQ',
            self::PersonalTecnico => 'Personal técnico',
        };
    }

    /** Nombre legible de un rol, incluso si no está entre los conocidos. */
    public static function etiqueta(?string $nombre): string
    {
        if (blank($nombre)) {
            return '—';
        }

        return self::tryFrom($nombre)?->label()
            ?? Str::ucfirst(str_replace('_', ' ', $nombre));
    }
}
