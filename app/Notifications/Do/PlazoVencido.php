<?php

namespace App\Notifications\Do;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Models\Do\ProcedimientoDo;
use Filament\Actions\Action;
use Filament\Notifications\Notification as NotificacionFilament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

/**
 * El plazo ya se incumplió. A diferencia del aviso anticipado, aquí no queda
 * margen: el procedimiento está fuera del tiempo que fija su prioridad.
 */
class PlazoVencido extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ProcedimientoDo $procedimiento,
        private readonly Carbon $fechaLimite,
        private readonly int $diasDeAtraso,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array<string, mixed> */
    public function toArray(object $notifiable): array
    {
        $pendiente = $this->procedimiento->estaEstandarizado()
            ? 'La verificación en campo'
            : 'La estandarización';

        return NotificacionFilament::make()
            ->title('Plazo vencido')
            ->body(sprintf(
                '%s de «%s» venció el %s (%s de atraso).',
                $pendiente,
                $this->procedimiento->nombre_actividad,
                $this->fechaLimite->format('d/m/Y'),
                $this->diasDeAtraso === 1 ? '1 día' : $this->diasDeAtraso.' días',
            ))
            ->icon('heroicon-o-exclamation-circle')
            ->color('danger')
            ->actions([
                Action::make('ver')
                    ->label('Ver procedimiento')
                    ->url(ProcedimientoDoResource::getUrl('edit', ['record' => $this->procedimiento]))
                    ->markAsRead(),
            ])
            ->getDatabaseMessage()
            // Permite reconocer avisos repetidos del mismo procedimiento.
            + ['procedimiento_id' => $this->procedimiento->getKey()];
    }
}
