<?php

namespace App\Services;

use App\Models\Documento;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class NotificacionDocumentoService
{
    public static function notificarCambioEstado(Documento $documento, string $estadoAnterior): void
    {
        $estado = $documento->estado;
        $titulo = $documento->titulo;

        $destinatarios = match ($estado) {
            'en_revision' => self::getAprobadores($documento),
            'aprobado' => self::getCreadorYResponsable($documento),
            'rechazado' => self::getCreadorYResponsable($documento),
            'divulgado' => self::getTodosLosGestores(),
            default => collect(),
        };

        if ($destinatarios->isEmpty()) {
            return;
        }

        $mensaje = match ($estado) {
            'en_revision' => "El documento \"{$titulo}\" requiere su revisión y aprobación.",
            'aprobado' => "El documento \"{$titulo}\" ha sido aprobado.",
            'rechazado' => "El documento \"{$titulo}\" ha sido rechazado y debe ser corregido.",
            'divulgado' => "El documento \"{$titulo}\" ha sido divulgado y está disponible.",
            default => "El documento \"{$titulo}\" cambió de estado a {$estado}.",
        };

        $color = match ($estado) {
            'aprobado' => 'success',
            'divulgado' => 'success',
            'rechazado' => 'danger',
            default => 'warning',
        };

        foreach ($destinatarios as $usuario) {
            Notification::make()
                ->title(self::tituloEstado($estado))
                ->body($mensaje)
                ->{$color}()
                ->sendToDatabase($usuario);
        }
    }

    private static function tituloEstado(string $estado): string
    {
        return match ($estado) {
            'en_revision' => 'Documento pendiente de revisión',
            'aprobado' => 'Documento aprobado',
            'rechazado' => 'Documento rechazado',
            'divulgado' => 'Nuevo documento divulgado',
            default => 'Cambio de estado en documento',
        };
    }

    private static function getAprobadores(Documento $documento): Collection
    {
        $usuarios = collect();

        if ($documento->aprobador_id) {
            $aprobador = User::find($documento->aprobador_id);
            if ($aprobador) {
                $usuarios->push($aprobador);
            }
        }

        if ($usuarios->isEmpty()) {
            $usuarios = User::role(['admin', 'super_admin'])->get();
        }

        return $usuarios;
    }

    private static function getCreadorYResponsable(Documento $documento): Collection
    {
        $ids = array_filter([$documento->created_by, $documento->responsable_id]);

        return User::whereIn('id', $ids)->get();
    }

    private static function getTodosLosGestores(): Collection
    {
        return User::role(['gestor_documental', 'admin', 'super_admin'])->get();
    }
}
