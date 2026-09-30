<?php

use App\Http\Controllers\Auth\AzureController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as UsuarioSocialite;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function cuentaAzure(string $correo, string $nombre = 'Usuario Prueba'): UsuarioSocialite
{
    $cuenta = Mockery::mock(UsuarioSocialite::class);
    $cuenta->shouldReceive('getEmail')->andReturn($correo);
    $cuenta->shouldReceive('getName')->andReturn($nombre);
    $cuenta->shouldReceive('getAvatar')->andReturn(null);

    return $cuenta;
}

function simularAzure(UsuarioSocialite $cuenta): void
{
    $driver = Mockery::mock('Laravel\Socialite\Two\AbstractProvider');
    $driver->shouldReceive('user')->andReturn($cuenta);

    Socialite::shouldReceive('driver')->with('azure')->andReturn($driver);
}

/** Rol mínimo para poder entrar al panel. */
function rolConAcceso(): Role
{
    Permission::findOrCreate('acceder panel admin', 'web');

    return Role::findOrCreate('personal_tecnico', 'web')
        ->givePermissionTo('acceder panel admin');
}

it('crea el usuario cuando entra por primera vez con su correo institucional', function () {
    simularAzure(cuentaAzure('nuevo@confipetrol.com', 'Ana Gómez'));

    $this->get(route('auth.azure.callback'))->assertRedirect();

    $usuario = User::where('email', 'nuevo@confipetrol.com')->first();

    expect($usuario)->not->toBeNull()
        ->and($usuario->name)->toBe('Ana Gómez')
        ->and($usuario->is_active)->toBeTrue();
});

it('autentica a quien ya tiene permisos', function () {
    $usuario = User::factory()->create(['email' => 'conrol@confipetrol.com', 'is_active' => true]);
    $usuario->assignRole(rolConAcceso());

    simularAzure(cuentaAzure('conrol@confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    $this->assertAuthenticatedAs($usuario->fresh());
});

it('reutiliza el usuario existente en lugar de duplicarlo', function () {
    $existente = User::factory()->create([
        'email' => 'gestor@confipetrol.com',
        'is_active' => true,
    ]);
    $existente->assignRole(rolConAcceso());

    simularAzure(cuentaAzure('gestor@confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    expect(User::where('email', 'gestor@confipetrol.com')->count())->toBe(1);

    $this->assertAuthenticatedAs($existente->fresh());
});

it('explica al usuario sin rol por qué no puede entrar', function () {
    simularAzure(cuentaAzure('sinrol@confipetrol.com', 'Sin Rol'));

    $this->get(route('auth.azure.callback'))
        ->assertRedirect(route('filament.admin.auth.login'))
        ->assertSessionHasErrors('email');

    // La cuenta queda creada para que el administrador solo tenga que darle un rol.
    expect(User::where('email', 'sinrol@confipetrol.com')->exists())->toBeTrue();

    $this->assertGuest();
});

it('devuelve al acceso con un aviso en lugar de una pantalla de error', function () {
    $sinPermisos = User::factory()->create(['is_active' => true]);

    $this->actingAs($sinPermisos)
        ->get('/admin')
        ->assertRedirect(route('filament.admin.auth.login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rechaza correos de otros dominios', function () {
    simularAzure(cuentaAzure('externo@gmail.com'));

    $this->get(route('auth.azure.callback'))
        ->assertRedirect(route('filament.admin.auth.login'));

    expect(User::where('email', 'externo@gmail.com')->exists())->toBeFalse();

    $this->assertGuest();
});

it('rechaza usuarios inactivos', function () {
    User::factory()->create([
        'email' => 'inactivo@confipetrol.com',
        'is_active' => false,
    ]);

    simularAzure(cuentaAzure('inactivo@confipetrol.com'));

    $this->get(route('auth.azure.callback'))
        ->assertRedirect(route('filament.admin.auth.login'));

    $this->assertGuest();
});

it('registra la fecha del último ingreso', function () {
    simularAzure(cuentaAzure('marca@confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    expect(User::where('email', 'marca@confipetrol.com')->first()->last_login_at)->not->toBeNull();
});

it('se considera configurado solo con un tenant propio', function (?string $tenant, bool $esperado) {
    config([
        'services.azure.client_id' => 'id',
        'services.azure.client_secret' => 'secreto',
        'services.azure.redirect' => 'https://ejemplo.test/auth/azure/callback',
        'services.azure.tenant' => $tenant,
    ]);

    expect(AzureController::estaConfigurado())->toBe($esperado);
})->with([
    'tenant de la organizacion' => ['641e2dc9-d106-45f6-bb66-c742d94c9104', true],
    'common admite cualquier cuenta' => ['common', false],
    'sin tenant' => [null, false],
]);

it('no se considera configurado si falta el secreto', function () {
    config([
        'services.azure.client_id' => 'id',
        'services.azure.client_secret' => null,
        'services.azure.redirect' => 'https://ejemplo.test/auth/azure/callback',
        'services.azure.tenant' => 'un-tenant',
    ]);

    expect(AzureController::estaConfigurado())->toBeFalse();
});

it('normaliza el correo a minúsculas', function () {
    simularAzure(cuentaAzure('Mayusculas@Confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    expect(User::where('email', 'mayusculas@confipetrol.com')->exists())->toBeTrue();
});
