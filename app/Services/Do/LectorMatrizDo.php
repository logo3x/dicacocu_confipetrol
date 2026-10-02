<?php

namespace App\Services\Do;

use Illuminate\Support\Carbon;
use OpenSpout\Reader\XLSX\Reader;

/**
 * Lee la hoja «DO» de la Matriz Integral (HSEQ-GCA1-F-17) y devuelve cada
 * actividad como un arreglo con los nombres de campo del sistema.
 *
 * La matriz reparte sus encabezados entre varias filas combinadas, así que el
 * mapeo va por letra de columna, que es lo estable del formato.
 */
class LectorMatrizDo
{
    /** Primera fila con datos: arriba están los títulos del formato. */
    private const PRIMERA_FILA_DATOS = 7;

    /** Columna (empezando en 1) de cada campo dentro de la hoja DO. */
    private const COLUMNAS = [
        'contrato' => 2,          // B
        'campo' => 3,             // C
        'nombre_actividad' => 4,  // D
        'fecha_identificacion' => 5,
        'personas_involucradas' => 6,
        'amenaza_riesgo_critico' => 7,
        'amenaza_equipos_criticos' => 8,
        'amenaza_impacto_ambiental' => 9,
        'amenaza_antecedentes' => 10,
        'amenaza_afecta_servicio' => 11,
        'amenaza_no_rutinaria' => 12,
        'codificado' => 16,            // P
        'fecha_codificacion' => 18,    // R
        'responsable_codificacion' => 19,
        'codigo_asignado' => 20,       // T
        'titulo_procedimiento' => 21,
        'version_actual' => 22,
        'ubicacion_acceso' => 23,
        'fecha_ultima_divulgacion' => 24,
        'personas_socializadas' => 25,
        'responsable_area' => 28,      // AB
        'fecha_programada_verificacion' => 29,
        'fecha_ejecutada_verificacion' => 30,
        'observador_operativo' => 31,
        'observador_hseq' => 32,
    ];

    /**
     * Filas de la matriz con los campos ya convertidos.
     *
     * @return array<int, array<string, mixed>>
     */
    public function leer(string $ruta): array
    {
        $lector = new Reader;
        $lector->open($ruta);

        $actividades = [];

        foreach ($lector->getSheetIterator() as $hoja) {
            if (mb_strtoupper(trim($hoja->getName())) !== 'DO') {
                continue;
            }

            foreach ($hoja->getRowIterator() as $numeroFila => $fila) {
                if ($numeroFila < self::PRIMERA_FILA_DATOS) {
                    continue;
                }

                $celdas = $fila->toArray();
                $actividad = $this->interpretarFila($celdas);

                // Una fila sin nombre de actividad es relleno del formato.
                if (blank($actividad['nombre_actividad'])) {
                    continue;
                }

                $actividad['fila'] = $numeroFila;
                $actividades[] = $actividad;
            }

            break;
        }

        $lector->close();

        return $actividades;
    }

    /**
     * @param  array<int, mixed>  $celdas
     * @return array<string, mixed>
     */
    private function interpretarFila(array $celdas): array
    {
        $valor = fn (string $campo): mixed => $celdas[self::COLUMNAS[$campo] - 1] ?? null;

        return [
            'contrato' => $this->texto($valor('contrato')),
            'campo' => $this->texto($valor('campo')),
            'nombre_actividad' => $this->texto($valor('nombre_actividad')),
            'fecha_identificacion' => $this->fecha($valor('fecha_identificacion')),
            'personas_involucradas' => $this->entero($valor('personas_involucradas')),

            'amenaza_riesgo_critico' => $this->bandera($valor('amenaza_riesgo_critico')),
            'amenaza_equipos_criticos' => $this->bandera($valor('amenaza_equipos_criticos')),
            'amenaza_impacto_ambiental' => $this->bandera($valor('amenaza_impacto_ambiental')),
            'amenaza_antecedentes' => $this->bandera($valor('amenaza_antecedentes')),
            'amenaza_afecta_servicio' => $this->bandera($valor('amenaza_afecta_servicio')),
            'amenaza_no_rutinaria' => $this->bandera($valor('amenaza_no_rutinaria')),

            'codificado' => $this->siNo($valor('codificado')),
            'fecha_codificacion' => $this->fecha($valor('fecha_codificacion')),
            'responsable_codificacion' => $this->texto($valor('responsable_codificacion')),
            'codigo_asignado' => $this->texto($valor('codigo_asignado')),
            'titulo_procedimiento' => $this->texto($valor('titulo_procedimiento')),
            'version_actual' => $this->entero($valor('version_actual')),
            'ubicacion_acceso' => $this->texto($valor('ubicacion_acceso')),

            'fecha_ultima_divulgacion' => $this->fecha($valor('fecha_ultima_divulgacion')),
            'personas_socializadas' => $this->entero($valor('personas_socializadas')),

            'responsable_area' => $this->texto($valor('responsable_area')),
            'fecha_programada_verificacion' => $this->fecha($valor('fecha_programada_verificacion')),
            'fecha_ejecutada_verificacion' => $this->fecha($valor('fecha_ejecutada_verificacion')),
            'observador_operativo' => $this->texto($valor('observador_operativo')),
            'observador_hseq' => $this->texto($valor('observador_hseq')),
        ];
    }

    /** La matriz escribe «SIN DATOS» o «NA» donde no hay valor. */
    private function texto(mixed $valor): ?string
    {
        if ($valor instanceof \DateTimeInterface) {
            return null;
        }

        $texto = trim((string) $valor);

        if ($texto === '' || in_array(mb_strtoupper($texto), ['SIN DATOS', 'NA', 'N/A', '0'], true)) {
            return null;
        }

        return $texto;
    }

    private function entero(mixed $valor): ?int
    {
        if ($valor === null || $valor === '' || $valor instanceof \DateTimeInterface) {
            return null;
        }

        return is_numeric($valor) ? (int) $valor : null;
    }

    /** Los criterios de amenaza vienen como 1 / 0. */
    private function bandera(mixed $valor): bool
    {
        return (int) $valor === 1;
    }

    private function siNo(mixed $valor): bool
    {
        return mb_strtoupper(trim((string) $valor)) === 'SI';
    }

    /**
     * Las fechas llegan como objeto o como número de serie de Excel. El 0 es
     * el relleno que la matriz usa cuando el dato aún no existe.
     */
    private function fecha(mixed $valor): ?Carbon
    {
        if ($valor instanceof \DateTimeInterface) {
            return Carbon::instance($valor)->startOfDay();
        }

        if (! is_numeric($valor) || (int) $valor <= 0) {
            return null;
        }

        // Excel cuenta desde 1899-12-30 por el bug del año bisiesto de 1900.
        return Carbon::create(1899, 12, 30)->addDays((int) $valor);
    }
}
