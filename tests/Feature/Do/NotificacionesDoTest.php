<?php

use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use App\Notifications\Do\PlazoPorVencer;
use App\Notifications\Do\PlazoVencido;
use App\Notifications\Do\VerificacionDeficiente;
use App\Services\Do\DestinatariosDo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('avisa cuando una verificacion sale deficiente', function () {
    Notification::fake();

    $responsable = User::factory()->create(['is_active' => true]);
    $procedimiento = ProcedimientoDo::factory()->create(['responsable_area_id' => $responsable->id]);

    // 5 de 11 respuestas afirmativas y pasos que no coinciden.
    EvaluacionF14::factory()->create([
        'procedimiento_id' => $procedimiento->id,
        'q1' => true, 'q2' => true, 'q3' => true, 'q4' => true, 'q5' => true,
        'pasos_segun_procedimiento' => 6,
        'pasos_en_observacion' => 4,
    ]);

    Notification::assertSentTo($responsable, VerificacionDeficiente::class);
});

it('no avisa cuando la verificacion sale bien', function () {
    Notification::fake();

    $responsable = User::factory()->create(['is_active' => true]);
    $procedimiento = ProcedimientoDo::factory()->create(['responsable_area_id' => $responsable->id]);

    EvaluacionF14::factory()->completa()->create(['procedimiento_id' => $procedimiento->id]);

    Notification::assertNothingSent();
});

it('avisa de los plazos que se cumplen en los proximos dias', function () {
    Notification::fake();

    $responsable = User::factory()->create(['is_active' => true]);

    ProcedimientoDo::factory()->prioridadAlta()->create([
        'responsable_area_id' => $responsable->id,
        // Prioridad alta: un mes de plazo, asi que vence dentro de 7 dias.
        'fecha_identificacion' => now()->addDays(7)->subMonth(),
    ]);

    $this->artisan('do:avisar-plazos')->assertSuccessful();

    Notification::assertSentTo($responsable, PlazoPorVencer::class);
});

it('avisa de los plazos ya vencidos', function () {
    Notification::fake();

    $responsable = User::factory()->create(['is_active' => true]);

    ProcedimientoDo::factory()->prioridadAlta()->create([
        'responsable_area_id' => $responsable->id,
        'fecha_identificacion' => now()->subMonths(4),
    ]);

    $this->artisan('do:avisar-plazos')->assertSuccessful();

    Notification::assertSentTo($responsable, PlazoVencido::class);
});

it('no vuelve a avisar lo mismo el mismo dia', function () {
    $responsable = User::factory()->create(['is_active' => true]);

    ProcedimientoDo::factory()->prioridadAlta()->create([
        'responsable_area_id' => $responsable->id,
        'fecha_identificacion' => now()->subMonths(4),
    ]);

    // El comando corre a diario: la segunda vuelta no debe repetir el aviso.
    $this->artisan('do:avisar-plazos')->assertSuccessful();
    $primera = $responsable->notifications()->count();

    $this->artisan('do:avisar-plazos')->assertSuccessful();

    expect($responsable->notifications()->count())->toBe($primera)
        ->and($primera)->toBeGreaterThan(0);
});

it('no avisa de un procedimiento ya estandarizado y verificado', function () {
    Notification::fake();

    $responsable = User::factory()->create(['is_active' => true]);

    ProcedimientoDo::factory()->prioridadAlta()->estandarizado()->create([
        'responsable_area_id' => $responsable->id,
        'fecha_programada_verificacion' => now()->subMonth(),
        'fecha_ejecutada_verificacion' => now()->subWeek(),
    ]);

    $this->artisan('do:avisar-plazos')->assertSuccessful();

    Notification::assertNothingSent();
});

it('avisa a quien hace seguimiento en el mismo contrato', function () {
    $procedimiento = ProcedimientoDo::factory()->create();

    Role::findOrCreate('responsable_hseq', 'web');

    $hseq = User::factory()->create([
        'is_active' => true,
        'contrato_id' => $procedimiento->contrato_id,
    ]);
    $hseq->assignRole('responsable_hseq');

    $ajeno = User::factory()->create(['is_active' => true, 'contrato_id' => null]);
    $ajeno->assignRole('responsable_hseq');

    $destinatarios = DestinatariosDo::para($procedimiento);

    expect($destinatarios->pluck('id'))->toContain($hseq->id)
        ->and($destinatarios->pluck('id'))->not->toContain($ajeno->id);
});

it('no avisa a usuarios inactivos', function () {
    $inactivo = User::factory()->create(['is_active' => false]);
    $procedimiento = ProcedimientoDo::factory()->create(['responsable_area_id' => $inactivo->id]);

    expect(DestinatariosDo::para($procedimiento)->pluck('id'))->not->toContain($inactivo->id);
});
