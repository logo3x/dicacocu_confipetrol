<?php

use App\Filament\Resources\Do\ProcedimientosDo\Schemas\ProcedimientoDoForm as Form;

it('avanza a la etapa siguiente', function (?string $actual, ?string $esperada) {
    expect(Form::siguienteEtapa($actual))->toBe($esperada);
})->with([
    'de DI a CA' => [Form::idEtapa(Form::ETAPA_DI), Form::idEtapa(Form::ETAPA_CA)],
    'de CA a CO' => [Form::idEtapa(Form::ETAPA_CA), Form::idEtapa(Form::ETAPA_CO)],
    'de CO a CU' => [Form::idEtapa(Form::ETAPA_CO), Form::idEtapa(Form::ETAPA_CU)],
    'CU es la ultima' => [Form::idEtapa(Form::ETAPA_CU), null],
    'sin etapa arranca en CA' => [null, Form::idEtapa(Form::ETAPA_CA)],
    'etapa desconocida cae en CA' => ['inexistente', Form::idEtapa(Form::ETAPA_CA)],
]);

it('genera el identificador que Filament espera en la URL', function () {
    expect(Form::idEtapa(Form::ETAPA_DI))->toBe('1-di::data::tab')
        ->and(Form::idEtapa(Form::ETAPA_CU))->toBe('4-cu::data::tab');
});

it('mantiene las cuatro etapas en orden', function () {
    expect(Form::ORDEN_ETAPAS)->toBe([
        Form::ETAPA_DI,
        Form::ETAPA_CA,
        Form::ETAPA_CO,
        Form::ETAPA_CU,
    ]);
});
