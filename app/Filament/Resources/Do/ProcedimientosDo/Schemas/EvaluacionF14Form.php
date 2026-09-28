<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Schemas;

use App\Enums\Do\CumpleRegla;
use App\Enums\Do\ReglaSalvaVidas;
use App\Models\User;
use App\Services\Do\CalculadoraDo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

/**
 * Formato de Acompañamiento y Verificación de Actividades (HSEQ-GCA1-F-14).
 */
class EvaluacionF14Form
{
    /** Las 11 preguntas del checklist, cada una vale 6,37%. */
    public const PREGUNTAS = [
        'q1' => '1. ¿Está disponible el Procedimiento en el frente de trabajo? ¿Es de fácil acceso?',
        'q2' => '2. ¿Usa correctamente los implementos de seguridad según lo indica la Matriz EPP-Procedimiento de trabajo?',
        'q3' => '3. ¿Identifica sus peligros y riesgos correcta y completamente según los formatos establecidos (AST, APR, IPERC cont.)?',
        'q4' => '4. ¿Están disponibles las herramientas y las usa correctamente?',
        'q5' => '5. ¿Mantiene el área de trabajo limpia y ordenada antes, durante y después de la ejecución?',
        'q6' => '6. ¿Aplica los controles necesarios para realizar la tarea de forma segura?',
        'q7' => '7. ¿El Procedimiento está actualizado, cuenta con la codificación y firmas de aprobación? ¿Se encuentra en buen estado?',
        'q8' => '8. ¿El Procedimiento es fácil de entendimiento?',
        'q9' => '9. ¿El procedimiento fue divulgado al personal que ejecuta la actividad?',
        'q10' => '10. ¿El personal que realiza la tarea cuenta con la capacitación? ¿Está certificado?',
        'q11' => '11. ¿El personal que realiza la tarea mostró habilidad durante la ejecución?',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Datos de la verificación')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('fecha_ejecucion')
                            ->label('Fecha de ejecución')
                            ->required()
                            ->default(now()),
                        TextInput::make('campo')->label('Campo')->maxLength(191),
                        TextInput::make('area')->label('Área')->maxLength(191),
                        Select::make('responsable_area_id')
                            ->label('Responsable del área')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        TextInput::make('nombre_actividad_observada')
                            ->label('Nombre de la actividad observada')
                            ->required()
                            ->maxLength(191),
                    ]),

                Section::make('Quien observa y/o visita')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('observador_id')
                            ->label('Nombre completo del observador')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('cargo_observador')->label('Cargo')->required()->maxLength(191),
                        Select::make('acompanante_id')
                            ->label('Nombre completo del acompañante')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->different('observador_id'),
                        TextInput::make('cargo_acompanante')->label('Cargo del acompañante')->maxLength(191),
                    ]),

                Section::make('Observación de la actividad ejecutada')
                    ->description('Describa el paso a paso de la actividad observada. Puede ser una actividad completa o parcial según su complejidad.')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('pasos_observados')
                            ->hiddenLabel()
                            ->simple(
                                TextInput::make('paso')->label('Paso')->required()->maxLength(500),
                            )
                            ->addActionLabel('Agregar paso')
                            ->maxItems(14)
                            ->defaultItems(0)
                            ->reorderable(),
                    ]),

                Section::make('Evaluación de la observación')
                    ->description('Cada ítem marcado como "Sí" equivale a 6,37%. El subtotal representa el 70% del cumplimiento total.')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        ...self::checklist(),

                        Text::make(fn (Get $get): string => 'Sub-Total: '.self::subtotal($get).'%')
                            ->weight('bold'),

                        Textarea::make('oportunidades_mejora')
                            ->label('Oportunidades de mejora detectadas')
                            ->rows(3),
                    ]),

                Section::make('Pregunta 12 — Coincidencia de pasos')
                    ->description('Equivale al 30% del cumplimiento total. Se otorga el puntaje completo solo si ambos valores coinciden.')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('pasos_segun_procedimiento')
                            ->label('Pasos según procedimiento')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true),
                        TextInput::make('pasos_en_observacion')
                            ->label('Pasos en la observación')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true),
                        Text::make(fn (Get $get): string => CalculadoraDo::pasosCoinciden(
                            self::entero($get('pasos_segun_procedimiento')),
                            self::entero($get('pasos_en_observacion')),
                        ) ? 'Coinciden: +30%' : 'No coinciden: +0%')
                            ->weight('bold'),
                        Text::make(fn (Get $get): string => 'Total % Cumplimiento: '.self::total($get).'%')
                            ->weight('bold'),
                        Text::make(fn (Get $get): string => 'Criterio de aprobación: '
                            .CalculadoraDo::criterioOpt(self::total($get))->label())
                            ->color(fn (Get $get): string => CalculadoraDo::criterioOpt(self::total($get))->color())
                            ->weight('bold'),
                    ]),

                Section::make('Análisis de la actividad')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('analisis_actividad')
                            ->label('Análisis de la actividad')
                            ->hiddenLabel()
                            ->rows(4),
                    ]),

                Section::make('Parte 2 — Inspección gerencial "Caminar la Planta"')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('aplica_inspeccion_gerencial')
                            ->label('¿Aplica inspección gerencial?')
                            ->live(),

                        Repeater::make('reglas')
                            ->label('Aplicación de las 12 Reglas que Salvan Vidas')
                            ->relationship()
                            ->schema([
                                Select::make('numero_regla')
                                    ->label('Regla')
                                    ->options(fn () => collect(ReglaSalvaVidas::cases())
                                        ->mapWithKeys(fn (ReglaSalvaVidas $r) => [$r->value => $r->value.'. '.$r->label()])
                                        ->all())
                                    ->required(),
                                Select::make('cumple')
                                    ->label('¿Cumple?')
                                    ->options(fn () => collect(CumpleRegla::cases())
                                        ->mapWithKeys(fn (CumpleRegla $c) => [$c->value => $c->label()])
                                        ->all())
                                    ->default(CumpleRegla::NoAplica->value)
                                    ->required(),
                                TextInput::make('observacion')->label('Observación')->maxLength(500),
                            ])
                            ->columns(1)
                            ->addActionLabel('Agregar regla')
                            ->defaultItems(0),

                        Textarea::make('hallazgos_positivos')->label('Hallazgos positivos')->rows(3),
                        Textarea::make('desvios_oportunidades')->label('Desvíos / oportunidades de mejora')->rows(3),

                        Repeater::make('acciones')
                            ->label('Acciones definidas y acordadas')
                            ->relationship()
                            ->schema([
                                Textarea::make('accion')->label('Acción')->required()->rows(2),
                                Select::make('responsable_id')
                                    ->label('Responsable')
                                    ->options(fn () => User::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload(),
                                DatePicker::make('fecha_cierre')->label('Fecha de cierre'),
                                DateTimePicker::make('cerrada_at')
                                    ->label('Cerrada el')
                                    ->helperText('Requiere el permiso de cierre de acciones')
                                    ->disabled(fn (): bool => ! auth()->user()?->can('cerrar acciones f14')),
                            ])
                            ->columns(1)
                            ->addActionLabel('Agregar acción')
                            ->defaultItems(0),
                    ])
                    ->visible(fn (Get $get): bool => (bool) $get('aplica_inspeccion_gerencial'))
                    ->collapsible(),
            ]);
    }

    /** @return array<int, Toggle|TextInput> */
    private static function checklist(): array
    {
        $componentes = [];

        foreach (self::PREGUNTAS as $campo => $pregunta) {
            $componentes[] = Toggle::make($campo)->label($pregunta)->live();
            $componentes[] = TextInput::make("{$campo}_observacion")
                ->label('Observación')
                ->maxLength(500);
        }

        return $componentes;
    }

    private static function subtotal(Get $get): float
    {
        $respuestas = [];

        foreach (array_keys(self::PREGUNTAS) as $campo) {
            $respuestas[$campo] = (bool) $get($campo);
        }

        return CalculadoraDo::subtotalChecklist($respuestas);
    }

    private static function total(Get $get): float
    {
        $respuestas = [];

        foreach (array_keys(self::PREGUNTAS) as $campo) {
            $respuestas[$campo] = (bool) $get($campo);
        }

        return CalculadoraDo::puntajeOpt(
            $respuestas,
            self::entero($get('pasos_segun_procedimiento')),
            self::entero($get('pasos_en_observacion')),
        );
    }

    private static function entero(mixed $valor): ?int
    {
        return is_numeric($valor) ? (int) $valor : null;
    }
}
