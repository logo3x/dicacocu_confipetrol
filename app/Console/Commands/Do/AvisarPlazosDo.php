<?php

namespace App\Console\Commands\Do;

use App\Models\Do\ProcedimientoDo;
use App\Notifications\Do\PlazoPorVencer;
use App\Notifications\Do\PlazoVencido;
use App\Services\Do\DestinatariosDo;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * Revisa los plazos del ciclo DICACOCU y avisa a los interesados.
 *
 * Pensado para ejecutarse una vez al día desde el programador de tareas.
 */
class AvisarPlazosDo extends Command
{
    /** Días antes del vencimiento en los que se envía el aviso anticipado. */
    private const HITOS_DE_AVISO = [15, 7, 1];

    protected $signature = 'do:avisar-plazos
                            {--dias= : Comprobar un solo hito en lugar de 15, 7 y 1}
                            {--simular : Mostrar lo que se enviaría sin enviarlo}';

    protected $description = 'Avisa de los procedimientos cuyo plazo está por cumplirse o ya venció';

    public function handle(): int
    {
        $hoy = Carbon::today();
        $simular = (bool) $this->option('simular');

        $hitos = $this->option('dias') !== null
            ? [(int) $this->option('dias')]
            : self::HITOS_DE_AVISO;

        $avisados = 0;

        foreach ($hitos as $dias) {
            $avisados += $this->avisarPorVencer($hoy, $dias, $simular);
        }

        $avisados += $this->avisarVencidos($hoy, $simular);

        $this->info($simular
            ? "Se enviarían {$avisados} avisos."
            : "Avisos enviados: {$avisados}.");

        return self::SUCCESS;
    }

    /** Procedimientos a los que les quedan exactamente $dias de plazo. */
    private function avisarPorVencer(Carbon $hoy, int $dias, bool $simular): int
    {
        $objetivo = $hoy->copy()->addDays($dias);
        $enviados = 0;

        foreach ($this->pendientes()->get() as $procedimiento) {
            $limite = $this->fechaLimite($procedimiento);

            if (! $limite || ! $limite->isSameDay($objetivo)) {
                continue;
            }

            $enviados += $this->enviar(
                $procedimiento,
                new PlazoPorVencer($procedimiento, $limite, $dias),
                PlazoPorVencer::class,
                $simular,
            );
        }

        return $enviados;
    }

    /** Procedimientos cuyo plazo ya pasó. */
    private function avisarVencidos(Carbon $hoy, bool $simular): int
    {
        $enviados = 0;

        foreach ($this->pendientes()->get() as $procedimiento) {
            $limite = $this->fechaLimite($procedimiento);

            if (! $limite || ! $limite->isBefore($hoy)) {
                continue;
            }

            $enviados += $this->enviar(
                $procedimiento,
                new PlazoVencido($procedimiento, $limite, (int) $limite->diffInDays($hoy)),
                PlazoVencido::class,
                $simular,
            );
        }

        return $enviados;
    }

    /**
     * Procedimientos que todavía esperan algo: estandarizarse, o verificarse
     * una vez estandarizados.
     *
     * @return Builder<ProcedimientoDo>
     */
    private function pendientes(): Builder
    {
        return ProcedimientoDo::query()
            ->where(fn (Builder $consulta) => $consulta
                ->where(fn (Builder $sin) => $sin
                    ->whereNull('codigo_asignado')
                    ->whereNotNull('fecha_limite_estandarizacion'))
                // Pendiente de verificar es tanto el que nunca se verificó
                // como el que ya tiene programada una ronda posterior a la
                // última ejecutada. Exigir que la fecha ejecutada estuviera
                // vacía dejaba al procedimiento sin avisos para siempre
                // después de su primera F-14.
                ->orWhere(fn (Builder $con) => $con
                    ->whereNotNull('codigo_asignado')
                    ->whereNotNull('fecha_programada_verificacion')
                    ->where(fn (Builder $ronda) => $ronda
                        ->whereNull('fecha_ejecutada_verificacion')
                        ->orWhereColumn('fecha_ejecutada_verificacion', '<', 'fecha_programada_verificacion'))));
    }

    /** La fecha que corresponde según la etapa en la que está. */
    private function fechaLimite(ProcedimientoDo $procedimiento): ?Carbon
    {
        $fecha = $procedimiento->estaEstandarizado()
            ? $procedimiento->fecha_programada_verificacion
            : $procedimiento->fecha_limite_estandarizacion;

        return $fecha?->copy()->startOfDay();
    }

    /**
     * Envía el aviso a los interesados, salvo que ya se les haya avisado hoy
     * de lo mismo: el comando corre a diario y no debe repetirse.
     */
    private function enviar(
        ProcedimientoDo $procedimiento,
        mixed $notificacion,
        string $tipo,
        bool $simular,
    ): int {
        $destinatarios = DestinatariosDo::para($procedimiento)
            ->reject(fn ($usuario) => $this->yaAvisadoHoy($usuario->getKey(), $tipo, $procedimiento));

        if ($destinatarios->isEmpty()) {
            return 0;
        }

        $this->line(sprintf(
            '  %s → %s (%d destinatario%s)',
            class_basename($tipo),
            $procedimiento->nombre_actividad,
            $destinatarios->count(),
            $destinatarios->count() === 1 ? '' : 's',
        ));

        if (! $simular) {
            Notification::send($destinatarios, $notificacion);
        }

        return $destinatarios->count();
    }

    /** Busca en las notificaciones del día una igual para ese procedimiento. */
    private function yaAvisadoHoy(int $usuarioId, string $tipo, ProcedimientoDo $procedimiento): bool
    {
        return DB::table('notifications')
            ->where('notifiable_id', $usuarioId)
            ->where('type', $tipo)
            ->whereDate('created_at', Carbon::today())
            // El patrón se cierra con la coma o la llave que siguen al número:
            // buscar solo "…:1" casaría también con 12, 13 o 100 y se saltaría
            // el aviso del procedimiento 1.
            ->where(fn ($consulta) => $consulta
                ->where('data', 'like', '%"procedimiento_id":'.$procedimiento->getKey().',%')
                ->orWhere('data', 'like', '%"procedimiento_id":'.$procedimiento->getKey().'}%'))
            ->exists();
    }
}
