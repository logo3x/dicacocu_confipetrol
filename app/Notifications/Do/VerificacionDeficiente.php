<?php

namespace App\Notifications\Do;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Models\Do\EvaluacionF14;
use Filament\Actions\Action;
use Filament\Notifications\Notification as NotificacionFilament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Una verificación en campo salió por debajo de lo esperado: quienes
 * acompañan el procedimiento deben enterarse para levantar un plan de acción.
 */
class VerificacionDeficiente extends Notification
{
    use Queueable;

    public function __construct(private readonly EvaluacionF14 $evaluacion) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        $procedimiento = $this->evaluacion->procedimiento;
        $criterio = $this->evaluacion->criterio_opt;

        $notificacion = NotificacionFilament::make()
            ->title('Verificación con resultado '.mb_strtolower($criterio->label()))
            ->body(sprintf(
                '%s obtuvo %s%% en la verificación del %s.',
                $procedimiento?->nombre_actividad ?? 'Un procedimiento',
                number_format((float) $this->evaluacion->puntaje_opt, 2),
                $this->evaluacion->fecha_ejecucion?->format('d/m/Y') ?? 'la última visita',
            ))
            ->icon('heroicon-o-exclamation-triangle')
            ->color($criterio->color());

        if ($procedimiento) {
            $notificacion->actions([
                Action::make('ver')
                    ->label('Ver procedimiento')
                    ->url(ProcedimientoDoResource::getUrl('edit', ['record' => $procedimiento]))
                    ->markAsRead(),
            ]);
        }

        return $notificacion->getDatabaseMessage()
            // Permite reconocer avisos repetidos del mismo procedimiento.
            + ['procedimiento_id' => $procedimiento?->getKey()];
    }
}
