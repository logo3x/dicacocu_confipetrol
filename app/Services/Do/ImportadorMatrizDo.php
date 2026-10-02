<?php

namespace App\Services\Do;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use App\Models\Do\ProcedimientoDo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Compara una Matriz Integral con lo que ya hay en el sistema y, cuando se
 * confirma, la aplica.
 *
 * Se analiza primero y se escribe después a propósito: la matriz puede traer
 * datos más viejos que el sistema, y sobrescribir sin avisar borraría
 * verificaciones o divulgaciones registradas aquí.
 */
class ImportadorMatrizDo
{
    /** Campos que se comparan y actualizan desde la matriz. */
    private const CAMPOS_COMPARABLES = [
        'fecha_identificacion', 'personas_involucradas',
        'amenaza_riesgo_critico', 'amenaza_equipos_criticos', 'amenaza_impacto_ambiental',
        'amenaza_antecedentes', 'amenaza_afecta_servicio', 'amenaza_no_rutinaria',
        'codificado', 'fecha_codificacion', 'codigo_asignado', 'titulo_procedimiento',
        'version_actual', 'ubicacion_acceso',
        'fecha_ultima_divulgacion', 'personas_socializadas',
        'fecha_programada_verificacion',
    ];

    public function __construct(private readonly LectorMatrizDo $lector = new LectorMatrizDo) {}

    /**
     * Qué pasaría al importar, sin tocar nada.
     *
     * @return array{nuevos: Collection<int, array<string, mixed>>, actualizables: Collection<int, array<string, mixed>>, sin_cambios: int, problemas: Collection<int, array<string, mixed>>}
     */
    public function analizar(string $ruta, int $anioCiclo): array
    {
        $nuevos = collect();
        $actualizables = collect();
        $problemas = collect();
        $sinCambios = 0;

        foreach ($this->lector->leer($ruta) as $fila) {
            $contrato = $this->buscarContrato($fila['contrato']);

            if (! $contrato) {
                $problemas->push([
                    'fila' => $fila['fila'],
                    'actividad' => $fila['nombre_actividad'],
                    'motivo' => "El contrato «{$fila['contrato']}» no está en el catálogo.",
                ]);

                continue;
            }

            $campo = $this->buscarCampo($contrato, $fila['campo']);

            if (! $campo) {
                $problemas->push([
                    'fila' => $fila['fila'],
                    'actividad' => $fila['nombre_actividad'],
                    'motivo' => "El campo «{$fila['campo']}» no pertenece al contrato {$contrato->nombre}.",
                ]);

                continue;
            }

            $existente = $this->buscarProcedimiento($fila, $contrato->id, $anioCiclo);

            if (! $existente) {
                $nuevos->push($fila + ['contrato_id' => $contrato->id, 'campo_id' => $campo->id]);

                continue;
            }

            $cambios = $this->diferencias($existente, $fila);

            if ($cambios === []) {
                $sinCambios++;

                continue;
            }

            $actualizables->push([
                'fila' => $fila['fila'],
                'procedimiento' => $existente,
                'actividad' => $fila['nombre_actividad'],
                'cambios' => $cambios,
                'datos' => $fila + ['contrato_id' => $contrato->id, 'campo_id' => $campo->id],
            ]);
        }

        return [
            'nuevos' => $nuevos,
            'actualizables' => $actualizables,
            'sin_cambios' => $sinCambios,
            'problemas' => $problemas,
        ];
    }

    /**
     * Aplica el análisis. Solo se tocan las filas indicadas, para que quien
     * importa pueda dejar fuera las que no quiere pisar.
     *
     * @param  array<int, int>  $filasAActualizar  Filas de la matriz a actualizar.
     * @return array{creados: int, actualizados: int}
     */
    public function aplicar(
        string $ruta,
        int $anioCiclo,
        bool $crearNuevos = true,
        array $filasAActualizar = [],
    ): array {
        $analisis = $this->analizar($ruta, $anioCiclo);
        $creados = 0;
        $actualizados = 0;

        DB::transaction(function () use ($analisis, $anioCiclo, $crearNuevos, $filasAActualizar, &$creados, &$actualizados) {
            if ($crearNuevos) {
                foreach ($analisis['nuevos'] as $fila) {
                    ProcedimientoDo::create($this->atributos($fila) + [
                        'anio_ciclo' => $anioCiclo,
                        'contrato_id' => $fila['contrato_id'],
                        'campo_id' => $fila['campo_id'],
                        'nombre_actividad' => $fila['nombre_actividad'],
                    ]);
                    $creados++;
                }
            }

            foreach ($analisis['actualizables'] as $pendiente) {
                if (! in_array($pendiente['fila'], $filasAActualizar, true)) {
                    continue;
                }

                $pendiente['procedimiento']->update($this->atributos($pendiente['datos']));
                $actualizados++;
            }
        });

        return ['creados' => $creados, 'actualizados' => $actualizados];
    }

    /**
     * Un procedimiento es el mismo si comparte código, o si comparte nombre
     * de actividad dentro del mismo contrato y ciclo.
     */
    private function buscarProcedimiento(array $fila, int $contratoId, int $anioCiclo): ?ProcedimientoDo
    {
        if (filled($fila['codigo_asignado'])) {
            $porCodigo = ProcedimientoDo::where('codigo_asignado', $fila['codigo_asignado'])->first();

            if ($porCodigo) {
                return $porCodigo;
            }
        }

        return ProcedimientoDo::query()
            ->where('contrato_id', $contratoId)
            ->where('anio_ciclo', $anioCiclo)
            ->whereRaw('UPPER(nombre_actividad) = ?', [mb_strtoupper($fila['nombre_actividad'])])
            ->first();
    }

    /**
     * Campos cuyo valor difiere, con el valor actual y el de la matriz.
     *
     * @return array<string, array{antes: mixed, despues: mixed}>
     */
    private function diferencias(ProcedimientoDo $procedimiento, array $fila): array
    {
        $cambios = [];

        foreach (self::CAMPOS_COMPARABLES as $campo) {
            if (! array_key_exists($campo, $fila)) {
                continue;
            }

            $nuevo = $fila[$campo];
            $actual = $procedimiento->{$campo};

            // La matriz deja vacíos los datos que aún no tiene: eso no es un
            // cambio, es ausencia de dato.
            if ($nuevo === null) {
                continue;
            }

            if ($this->normalizar($actual) === $this->normalizar($nuevo)) {
                continue;
            }

            $cambios[$campo] = [
                'antes' => $this->normalizar($actual),
                'despues' => $this->normalizar($nuevo),
            ];
        }

        return $cambios;
    }

    private function normalizar(mixed $valor): mixed
    {
        if ($valor instanceof Carbon || $valor instanceof \DateTimeInterface) {
            return Carbon::instance($valor)->format('Y-m-d');
        }

        if (is_bool($valor)) {
            return $valor ? '1' : '0';
        }

        return $valor === null ? null : (string) $valor;
    }

    /**
     * Solo los campos que la matriz manda, para no pisar lo capturado aquí.
     *
     * @return array<string, mixed>
     */
    private function atributos(array $fila): array
    {
        $atributos = [];

        foreach (self::CAMPOS_COMPARABLES as $campo) {
            if (array_key_exists($campo, $fila) && $fila[$campo] !== null) {
                $atributos[$campo] = $fila[$campo];
            }
        }

        return $atributos;
    }

    private function buscarContrato(?string $nombre): ?Contrato
    {
        if (blank($nombre)) {
            return null;
        }

        return Contrato::query()
            ->whereRaw('UPPER(nombre) = ?', [mb_strtoupper($nombre)])
            ->orWhereRaw('UPPER(codigo) = ?', [mb_strtoupper($nombre)])
            ->first();
    }

    private function buscarCampo(Contrato $contrato, ?string $nombre): ?Campo
    {
        if (blank($nombre)) {
            return null;
        }

        return Campo::query()
            ->where('contrato_id', $contrato->id)
            ->whereRaw('UPPER(nombre) = ?', [mb_strtoupper($nombre)])
            ->first();
    }
}
