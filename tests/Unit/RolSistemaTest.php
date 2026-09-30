<?php

use App\Enums\RolSistema;

it('traduce el identificador del rol a un nombre legible', function (string $identificador, string $esperado) {
    expect(RolSistema::etiqueta($identificador))->toBe($esperado);
})->with([
    ['super_admin', 'Superadministrador'],
    ['gestor_documental', 'Gestor documental'],
    ['lider_om', 'Líder O&M'],
    ['responsable_hseq', 'Responsable HSEQ'],
    ['personal_tecnico', 'Personal técnico'],
]);

it('da un nombre presentable a un rol que no conoce', function () {
    expect(RolSistema::etiqueta('rol_nuevo_cualquiera'))->toBe('Rol nuevo cualquiera');
});

it('devuelve un guion cuando no hay rol', function () {
    expect(RolSistema::etiqueta(null))->toBe('—')
        ->and(RolSistema::etiqueta(''))->toBe('—');
});

it('cubre todos los roles que existen en el sistema', function () {
    $delSistema = collect(RolSistema::cases())->pluck('value')->sort()->values()->all();

    expect($delSistema)->toBe([
        'admin',
        'calidad_corporativa',
        'gestor_documental',
        'lider_om',
        'operativo',
        'personal_tecnico',
        'responsable_hseq',
        'super_admin',
    ]);
});
