<?php

use App\Enums\Do\CriterioAmenaza;
use App\Enums\Do\CriterioOpt;
use App\Enums\Do\PrioridadDo;
use App\Services\Do\CalculadoraDo;

describe('puntaje de prioridad (Matriz DO)', function () {
    it('suma los pesos de los criterios marcados', function () {
        $puntaje = CalculadoraDo::puntajePrioridad([
            CriterioAmenaza::RiesgoCritico->value => true,
            CriterioAmenaza::EquiposCriticos->value => true,
            CriterioAmenaza::ImpactoAmbiental->value => true,
            CriterioAmenaza::Antecedentes->value => true,
            CriterioAmenaza::AfectaServicio->value => true,
            CriterioAmenaza::NoRutinaria->value => false,
        ]);

        // Caso de la fila 8 del Excel: 30+15+15+20+10 = 90
        expect($puntaje)->toBe(90);
    });

    it('devuelve 100 con todos los criterios marcados', function () {
        $criterios = [];

        foreach (CriterioAmenaza::cases() as $criterio) {
            $criterios[$criterio->value] = true;
        }

        expect(CalculadoraDo::puntajePrioridad($criterios))->toBe(100);
    });

    it('devuelve 0 sin criterios marcados', function () {
        expect(CalculadoraDo::puntajePrioridad([]))->toBe(0);
    });
});

describe('clasificación de prioridad', function () {
    it('clasifica según los cortes del Excel', function (int $puntaje, PrioridadDo $esperada) {
        expect(PrioridadDo::desdePuntaje($puntaje))->toBe($esperada);
    })->with([
        'cero es bajo' => [0, PrioridadDo::Bajo],
        'limite superior bajo' => [59, PrioridadDo::Bajo],
        'limite inferior medio' => [60, PrioridadDo::Medio],
        'limite superior medio' => [79, PrioridadDo::Medio],
        'limite inferior alto' => [80, PrioridadDo::Alto],
        'maximo es alto' => [100, PrioridadDo::Alto],
    ]);

    it('deriva el plazo de estandarización', function (PrioridadDo $prioridad, int $meses) {
        expect($prioridad->plazoEstandarizacionMeses())->toBe($meses);
    })->with([
        [PrioridadDo::Alto, 1],
        [PrioridadDo::Medio, 2],
        [PrioridadDo::Bajo, 4],
    ]);

    it('deriva la frecuencia de verificación', function (PrioridadDo $prioridad, int $meses) {
        expect($prioridad->frecuenciaVerificacionMeses())->toBe($meses);
    })->with([
        [PrioridadDo::Alto, 6],
        [PrioridadDo::Medio, 9],
        [PrioridadDo::Bajo, 12],
    ]);
});

describe('cobertura de socialización', function () {
    it('calcula el porcentaje de cobertura', function () {
        expect(CalculadoraDo::coberturaSocializacion(15, 20))->toBe(75.0);
    });

    it('devuelve 0 cuando no hay personal involucrado', function () {
        expect(CalculadoraDo::coberturaSocializacion(5, 0))->toBe(0.0)
            ->and(CalculadoraDo::coberturaSocializacion(5, null))->toBe(0.0);
    });

    it('no supera el 100% si se socializa a más personas de las involucradas', function () {
        expect(CalculadoraDo::coberturaSocializacion(25, 20))->toBe(100.0);
    });
});

describe('puntaje OPT (formato F-14)', function () {
    it('otorga 6,37% por cada respuesta afirmativa', function () {
        expect(CalculadoraDo::subtotalChecklist(['q1' => true, 'q2' => true, 'q3' => false]))->toBe(12.74);
    });

    it('alcanza 100,07 con las 11 preguntas y los pasos coincidentes', function () {
        $respuestas = array_fill_keys(
            ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11'],
            true,
        );

        expect(CalculadoraDo::puntajeOpt($respuestas, 8, 8))->toBe(100.07);
    });

    it('no suma la pregunta 12 si los pasos no coinciden', function () {
        $respuestas = array_fill_keys(
            ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11'],
            true,
        );

        expect(CalculadoraDo::puntajeOpt($respuestas, 8, 6))->toBe(70.07);
    });

    it('trata los pasos nulos como no coincidentes', function () {
        expect(CalculadoraDo::pasosCoinciden(null, null))->toBeFalse()
            ->and(CalculadoraDo::pasosCoinciden(5, null))->toBeFalse();
    });
});

describe('criterio de aprobación OPT', function () {
    it('clasifica según los cortes del Excel', function (?float $puntaje, CriterioOpt $esperado) {
        expect(CriterioOpt::desdePuntaje($puntaje))->toBe($esperado);
    })->with([
        'sin evaluaciones' => [null, CriterioOpt::SinDatos],
        'cero es sin datos' => [0.0, CriterioOpt::SinDatos],
        'deficiente' => [45.0, CriterioOpt::Deficiente],
        'limite deficiente' => [59.0, CriterioOpt::Deficiente],
        'limite inferior regular' => [59.5, CriterioOpt::Regular],
        'limite superior regular' => [79.0, CriterioOpt::Regular],
        'bueno' => [85.0, CriterioOpt::Bueno],
        'limite superior bueno' => [94.0, CriterioOpt::Bueno],
        'excelente' => [95.0, CriterioOpt::Excelente],
        'maximo posible' => [100.07, CriterioOpt::Excelente],
    ]);

    it('exige plan de acción salvo en excelente', function () {
        expect(CriterioOpt::Excelente->requierePlanDeAccion())->toBeFalse()
            ->and(CriterioOpt::Bueno->requierePlanDeAccion())->toBeTrue()
            ->and(CriterioOpt::Deficiente->requierePlanDeAccion())->toBeTrue();
    });
});
