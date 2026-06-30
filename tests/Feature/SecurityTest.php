<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'acceder panel admin', 'guard_name' => 'web']);
});

// ── Headers de seguridad ──────────────────────────────────────────────────────

test('panel login devuelve headers de seguridad', function () {
    $response = $this->get('/admin/login');

    // El middleware debe añadir headers independientemente del status
    $response
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-XSS-Protection', '1; mode=block')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
});

test('ruta de salud devuelve headers de seguridad', function () {
    $response = $this->get('/up');

    $response
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
});

// ── Autenticación del panel ───────────────────────────────────────────────────

test('panel admin requiere autenticación', function () {
    $response = $this->get('/admin');

    $response->assertRedirectToRoute('filament.admin.auth.login');
});

test('usuario inactivo no puede acceder al panel', function () {
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

    $user = User::factory()->create(['is_active' => false]);
    $user->assignRole('admin');
    $user->givePermissionTo('acceder panel admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('usuario activo sin permiso no puede acceder al panel', function () {
    $user = User::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('usuario activo con permiso puede acceder al panel', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->givePermissionTo('acceder panel admin');

    $this->actingAs($user)
        ->get('/admin')
        ->assertSuccessful();
});

// ── Rutas públicas ────────────────────────────────────────────────────────────

test('panel login es accesible sin autenticación', function () {
    $this->get('/admin/login')->assertSuccessful();
});

test('ruta de salud responde 200', function () {
    $this->get('/up')->assertSuccessful();
});

// ── Panel no expone errores de debug ─────────────────────────────────────────

test('rutas inexistentes devuelven 404 sin stack trace', function () {
    $response = $this->get('/admin/ruta-inexistente-xyz');

    $response->assertStatus(404);

    // Sin stack trace en el body (debug desactivado en testing)
    $this->assertStringNotContainsString('Whoops', $response->getContent() ?? '');
});
