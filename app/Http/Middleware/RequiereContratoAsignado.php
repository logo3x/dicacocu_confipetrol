<?php

namespace App\Http\Middleware;

use App\Filament\Pages\SeleccionarContrato;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Quien entra por primera vez con su correo institucional indica su contrato
 * antes de usar el panel; los administradores no lo necesitan.
 */
class RequiereContratoAsignado
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (! $usuario || $usuario->contrato_id || $usuario->veTodosLosContratos()) {
            return $next($request);
        }

        $destino = SeleccionarContrato::getUrl(panel: 'admin');

        if ($request->url() === $destino || $request->routeIs('filament.admin.auth.*')) {
            return $next($request);
        }

        return redirect()->to($destino);
    }
}
