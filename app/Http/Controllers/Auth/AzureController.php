<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * Inicio de sesión con la cuenta institucional de Confipetrol (Microsoft Entra ID).
 */
class AzureController extends Controller
{
    public function redirigir(): RedirectResponse
    {
        if (! self::estaConfigurado()) {
            return redirect()
                ->route('filament.admin.auth.login')
                ->withErrors(['email' => 'El inicio de sesión institucional no está configurado.']);
        }

        return Socialite::driver('azure')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $cuenta = Socialite::driver('azure')->user();
        } catch (Throwable $e) {
            Log::warning('Fallo el inicio de sesión con Azure', ['error' => $e->getMessage()]);

            return $this->rechazar('No se pudo completar el inicio de sesión institucional. Intente de nuevo.');
        }

        $correo = Str::lower((string) $cuenta->getEmail());

        if (blank($correo)) {
            return $this->rechazar('La cuenta institucional no tiene un correo asociado.');
        }

        if (! $this->dominioPermitido($correo)) {
            return $this->rechazar('Solo se permite el ingreso con cuentas @'.config('services.azure.dominio_permitido').'.');
        }

        $usuario = User::firstOrNew(['email' => $correo]);

        if ($usuario->exists && ! $usuario->is_active) {
            return $this->rechazar('Su usuario está inactivo. Contacte al administrador.');
        }

        $usuario->fill([
            'name' => $cuenta->getName() ?: $usuario->name ?: $correo,
            'avatar_url' => $cuenta->getAvatar() ?: $usuario->avatar_url,
            'last_login_at' => now(),
        ]);

        if (! $usuario->exists) {
            $usuario->is_active = true;
            $usuario->email_verified_at = now();
            $usuario->password = Str::password(32);
        }

        $usuario->save();

        Auth::login($usuario, remember: true);

        return redirect()->intended(Filament::getPanel('admin')->getUrl());
    }

    /** El botón solo se muestra cuando hay credenciales configuradas. */
    public static function estaConfigurado(): bool
    {
        return filled(config('services.azure.client_id'))
            && filled(config('services.azure.client_secret'))
            && filled(config('services.azure.redirect'));
    }

    private function dominioPermitido(string $correo): bool
    {
        $dominio = config('services.azure.dominio_permitido');

        return blank($dominio) || Str::endsWith($correo, '@'.Str::lower($dominio));
    }

    private function rechazar(string $mensaje): RedirectResponse
    {
        Auth::logout();

        return redirect()
            ->route('filament.admin.auth.login')
            ->withErrors(['email' => $mensaje]);
    }
}
