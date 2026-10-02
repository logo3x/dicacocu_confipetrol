<?php

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use App\Models\Do\ProcedimientoDo;
use App\Services\Do\ImportadorMatrizDo;
use App\Services\Do\LectorMatrizDo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function matrizDePrueba(): string
{
    return base_path('tests/Fixtures/matriz-prueba.xlsx');
}

function catalogoDePrueba(): Contrato
{
    $contrato = Contrato::factory()->create(['nombre' => 'CONTRATO PRUEBA', 'activo' => true]);
    Campo::factory()->create(['contrato_id' => $contrato->id, 'nombre' => 'CAMPO PRUEBA', 'activo' => true]);

    return $contrato;
}

it('lee las actividades de la hoja DO', function () {
    $filas = (new LectorMatrizDo)->leer(matrizDePrueba());

    expect($filas)->toHaveCount(3)
        ->and($filas[0]['nombre_actividad'])->toBe('Izaje de cargas criticas')
        ->and($filas[0]['personas_involucradas'])->toBe(8);
});

it('convierte las fechas que Excel guarda como numero de serie', function () {
    $filas = (new LectorMatrizDo)->leer(matrizDePrueba());

    // 46204 es el 1 de julio de 2026 en el calendario de Excel.
    expect($filas[0]['fecha_identificacion']->format('d/m/Y'))->toBe('01/07/2026');
});

it('interpreta los criterios de amenaza y el si/no de codificado', function () {
    $filas = (new LectorMatrizDo)->leer(matrizDePrueba());

    expect($filas[0]['amenaza_riesgo_critico'])->toBeTrue()
        ->and($filas[0]['amenaza_equipos_criticos'])->toBeTrue()
        ->and($filas[0]['amenaza_impacto_ambiental'])->toBeFalse()
        ->and($filas[0]['codificado'])->toBeTrue()
        ->and($filas[1]['codificado'])->toBeFalse();
});

it('crea los procedimientos y les calcula la prioridad', function () {
    catalogoDePrueba();

    $resultado = (new ImportadorMatrizDo)->aplicar(matrizDePrueba(), 2026);

    expect($resultado['creados'])->toBe(2);

    $izaje = ProcedimientoDo::where('nombre_actividad', 'Izaje de cargas criticas')->first();

    // 30 (riesgo critico) + 15 (equipos) + 20 (antecedentes) = 65 -> MEDIO.
    expect($izaje->puntaje_prioridad)->toBe(65)
        ->and($izaje->prioridad->value)->toBe('medio')
        ->and($izaje->codigo_asignado)->toBe('HSEQ-IZA-P-01');
});

it('avisa de las filas cuyo contrato no esta en el catalogo', function () {
    catalogoDePrueba();

    $analisis = (new ImportadorMatrizDo)->analizar(matrizDePrueba(), 2026);

    expect($analisis['problemas'])->toHaveCount(1)
        ->and($analisis['problemas']->first()['actividad'])->toBe('Actividad huerfana')
        ->and($analisis['problemas']->first()['motivo'])->toContain('no está en el catálogo');
});

it('no duplica nada al importar dos veces la misma matriz', function () {
    catalogoDePrueba();

    $importador = new ImportadorMatrizDo;
    $importador->aplicar(matrizDePrueba(), 2026);

    $segunda = $importador->analizar(matrizDePrueba(), 2026);

    expect($segunda['nuevos'])->toBeEmpty()
        ->and($segunda['actualizables'])->toBeEmpty()
        ->and($segunda['sin_cambios'])->toBe(2)
        ->and(ProcedimientoDo::count())->toBe(2);
});

it('muestra que cambiaria antes de tocar nada', function () {
    catalogoDePrueba();

    $importador = new ImportadorMatrizDo;
    $importador->aplicar(matrizDePrueba(), 2026);

    $procedimiento = ProcedimientoDo::where('codigo_asignado', 'HSEQ-IZA-P-01')->first();
    $procedimiento->update(['personas_involucradas' => 99]);

    $analisis = $importador->analizar(matrizDePrueba(), 2026);

    expect($analisis['actualizables'])->toHaveCount(1);

    $cambios = $analisis['actualizables']->first()['cambios'];

    expect($cambios)->toHaveKey('personas_involucradas')
        ->and($cambios['personas_involucradas']['antes'])->toBe('99')
        ->and($cambios['personas_involucradas']['despues'])->toBe('8');

    // Analizar no escribe: el dato sigue como lo dejó el usuario.
    expect($procedimiento->fresh()->personas_involucradas)->toBe(99);
});

it('solo actualiza las filas que se le indican', function () {
    catalogoDePrueba();

    $importador = new ImportadorMatrizDo;
    $importador->aplicar(matrizDePrueba(), 2026);

    $procedimiento = ProcedimientoDo::where('codigo_asignado', 'HSEQ-IZA-P-01')->first();
    $procedimiento->update(['personas_involucradas' => 99]);

    // Sin indicar filas no se actualiza nada.
    $sinFilas = $importador->aplicar(matrizDePrueba(), 2026, crearNuevos: false);
    expect($sinFilas['actualizados'])->toBe(0)
        ->and($procedimiento->fresh()->personas_involucradas)->toBe(99);

    // Indicando la fila sí.
    $conFila = $importador->aplicar(matrizDePrueba(), 2026, crearNuevos: false, filasAActualizar: [7]);
    expect($conFila['actualizados'])->toBe(1)
        ->and($procedimiento->fresh()->personas_involucradas)->toBe(8);
});

it('reconoce el mismo procedimiento aunque le cambien el nombre, por su codigo', function () {
    catalogoDePrueba();

    $importador = new ImportadorMatrizDo;
    $importador->aplicar(matrizDePrueba(), 2026);

    ProcedimientoDo::where('codigo_asignado', 'HSEQ-IZA-P-01')
        ->update(['nombre_actividad' => 'Nombre corregido a mano']);

    $analisis = $importador->analizar(matrizDePrueba(), 2026);

    // Se reconoce por el código, así que no se crea un duplicado.
    expect($analisis['nuevos'])->toBeEmpty();
});
