<?php

use App\Filament\Pages\SeleccionarContrato;
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

it('da el rol basico a quien entra por primera vez y lo deja pasar', function () {
    rolConAcceso();

    simularAzure(cuentaAzure('nueva@confipetrol.com', 'Nueva Persona'));

    $this->get(route('auth.azure.callback'));

    $usuario = User::where('email', 'nueva@confipetrol.com')->first();

    expect($usuario->hasRole('personal_tecnico'))->toBeTrue();

    $this->assertAuthenticatedAs($usuario);
});

it('lleva a indicar el contrato antes de usar el panel', function () {
    rolConAcceso();

    simularAzure(cuentaAzure('sincontrato@confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    // Entra, pero no puede usar el panel hasta decir a que contrato pertenece.
    $this->get('/admin')->assertRedirect(SeleccionarContrato::getUrl(panel: 'admin'));
});

it('no toca los roles de quien ya los tiene', function () {
    rolConAcceso();
    Permission::findOrCreate('acceder panel admin', 'web');
    Role::findOrCreate('admin', 'web')->givePermissionTo('acceder panel admin');

    $usuario = User::factory()->create(['email' => 'jefe@confipetrol.com', 'is_active' => true]);
    $usuario->assignRole('admin');

    simularAzure(cuentaAzure('jefe@confipetrol.com'));

    $this->get(route('auth.azure.callback'));

    expect($usuario->fresh()->getRoleNames()->all())->toBe(['admin']);
});

it('avisa en lugar de romper si el rol inicial no existe', function () {
    config(['services.azure.rol_inicial' => 'un_rol_que_no_existe']);

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
