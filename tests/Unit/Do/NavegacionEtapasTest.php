<?php

use App\Filament\Resources\Do\ProcedimientosDo\Schemas\ProcedimientoDoForm;

it('avanza a la etapa siguiente', function (?string $actual, ?string $esperada) {
    expect(ProcedimientoDoForm::siguienteEtapa($actual))->toBe($esperada);
})->with([
    'de DI a CA' => [ProcedimientoDoForm::TAB_DI, ProcedimientoDoForm::TAB_CA],
    'de CA a CO' => [ProcedimientoDoForm::TAB_CA, ProcedimientoDoForm::TAB_CO],
    'de CO a CU' => [ProcedimientoDoForm::TAB_CO, ProcedimientoDoForm::TAB_CU],
    'CU es la ultima' => [ProcedimientoDoForm::TAB_CU, null],
    'sin etapa arranca en DI' => [null, ProcedimientoDoForm::TAB_CA],
    'etapa desconocida cae en CA' => ['inexistente', ProcedimientoDoForm::TAB_CA],
]);

it('mantiene las cuatro etapas en orden', function () {
    expect(ProcedimientoDoForm::ORDEN_ETAPAS)->toBe([
        ProcedimientoDoForm::TAB_DI,
        ProcedimientoDoForm::TAB_CA,
        ProcedimientoDoForm::TAB_CO,
        ProcedimientoDoForm::TAB_CU,
    ]);
});
