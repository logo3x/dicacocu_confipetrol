<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    public static function log(string $event, array $context = []): void
    {
        Log::channel('security')->warning($event, array_merge([
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toIso8601String(),
        ], $context));
    }

    public static function loginFailed(string $email): void
    {
        self::log('login_failed', ['email' => $email]);
    }

    public static function accessDenied(string $resource, int|string $resourceId = ''): void
    {
        self::log('access_denied', ['resource' => $resource, 'resource_id' => $resourceId]);
    }

    public static function workflowTransition(string $documentoCodigo, string $estadoAnterior, string $estadoNuevo): void
    {
        self::log('workflow_transition', [
            'documento' => $documentoCodigo,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
        ]);
    }
}
