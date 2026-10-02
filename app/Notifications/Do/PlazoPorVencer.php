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
 * Aviso anticipado: el plazo de estandarización o de verificación se cumple
 * pronto y todavía hay margen para actuar.
 */
class PlazoPorVencer extends Notification
{
    use Queueable;

    public function __construct(
        private readonly ProcedimientoDo $procedimiento,
        private readonly Carbon $fechaLimite,
        private readonly int $diasRestantes,
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
            ? 'la verificación en campo'
            : 'la estandarización';

        return NotificacionFilament::make()
            ->title('Plazo próximo a cumplirse')
            ->body(sprintf(
                '%s: %s vence el %s (%s).',
                $this->procedimiento->nombre_actividad,
                $pendiente,
                $this->fechaLimite->format('d/m/Y'),
                $this->diasRestantes === 1 ? 'mañana' : 'en '.$this->diasRestantes.' días',
            ))
            ->icon('heroicon-o-clock')
            ->color('warning')
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
