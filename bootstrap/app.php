<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Quien llega al panel sin permisos veria un 403 sin explicacion; se le
        // devuelve al acceso con el motivo.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() !== 403 || ! $request->is('admin*') || $request->expectsJson()) {
                return null;
            }

            // Solo cierra la sesion de quien habia entrado: invalidarla por
            // completo borraria el token del formulario de acceso.
            Auth::logout();

            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors(['email' => 'Su cuenta aún no tiene permisos para ingresar. Solicite al administrador que le asigne un rol.']);
        });
    })->create();
