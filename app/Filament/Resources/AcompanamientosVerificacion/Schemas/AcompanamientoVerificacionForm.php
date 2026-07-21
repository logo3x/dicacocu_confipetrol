<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\Schemas;

use App\Enums\AnalisisActividad;
use App\Models\AcompanamientoVerificacion;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AcompanamientoVerificacionForm
{
    private static function preguntasChecklist(): array
    {
        $preguntas = [
            'q1_procedimiento_disponible' => '1. ¿Está disponible el Procedimiento en el frente de trabajo? ¿Es de fácil acceso?',
            'q2_usa_epp_correctamente' => '2. ¿Usa correctamente los implementos de seguridad según lo indica la Matriz EPP-Procedimiento de trabajo?',
            'q3_identifica_peligros_riesgos' => '3. ¿Identifica sus peligros y riesgos correcta y completamente según los formatos establecidos (AST, APR, IPERC cont.)?',
            'q4_herramientas_disponibles' => '4. ¿Están disponibles las herramientas y las usa correctamente?',
            'q5_area_limpia_ordenada' => '5. ¿Mantiene el área de trabajo limpia y ordenada antes, durante y después de la ejecución?',
            'q6_aplica_controles' => '6. ¿Aplica los Controles necesarios para realizar la tarea de forma segura?',
            'q7_procedimiento_actualizado' => '7. ¿El Procedimiento está actualizado, cuenta con la codificación y firmas de aprobación? ¿Se encuentra en buen estado?',
            'q8_procedimiento_facil_entendimiento' => '8. ¿El Procedimiento es fácil de entendimiento?',
            'q9_procedimiento_divulgado' => '9. ¿El procedimiento fue divulgado al personal que ejecuta la actividad?',
            'q10_personal_capacitado_certificado' => '10. ¿El personal que realiza la tarea cuenta con la capacitación? ¿Está certificado?',
            'q11_personal_mostro_habilidad' => '11. ¿El personal que realiza la tarea mostró habilidad durante la ejecución?',
        ];

        return collect($preguntas)
            ->map(fn (string $label, string $campo) => Toggle::make($campo)
                ->label($label)
                ->live()
                ->inline(false))
            ->values()
            ->all();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación del acompañamiento')
                    ->columns(2)
                    ->schema([
                        Select::make('actividad_id')
                            ->label('Actividad observada')
                            ->relationship('actividad', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        DatePicker::make('fecha_ejecucion')
                            ->label('Fecha de ejecución')
                            ->default(now())
                            ->required(),

                        Select::make('tipo_verificacion')
                            ->label('Tipo de verificación')
                            ->options([
                                'verificacion_cumplimiento_do' => '1. Verificación Cumplimiento DO',
                                'inspeccion_gerencial_caminar_planta' => '2. Inspección Gerencial — Caminar la Planta',
                            ])
                            ->helperText('Si selecciona Inspección Gerencial, se habilitará la Parte 2 (12 Reglas que Salvan Vidas) al guardar.')
                            ->required(),

                        TextInput::make('campo')
                            ->label('Campo')
                            ->maxLength(255),

                        TextInput::make('area')
                            ->label('Área')
                            ->maxLength(255),

                        Select::make('responsable_area_id')
                            ->label('Responsable del área')
                            ->relationship('responsableArea', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Quién observa y/o visita')
                    ->columns(2)
                    ->schema([
                        Select::make('observador_id')
                            ->label('Nombre completo (observador)')
                            ->relationship('observador', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('cargo_observador')
                            ->label('Cargo')
                            ->maxLength(255),

                        Select::make('acompanante_id')
                            ->label('Nombre completo de acompañante')
                            ->relationship('acompanante', 'name')
                            ->searchable()
                            ->preload(),

                        TextInput::make('cargo_acompanante')
                            ->label('Cargo')
                            ->maxLength(255),
                    ]),

                Section::make('Observación de la actividad ejecutada')
                    ->description('Describa el paso a paso de la actividad observada (puede ser completa o parcial).')
                    ->schema([
                        Repeater::make('pasos_observados')
                            ->label('Paso a paso observado')
                            ->simple(TextInput::make('paso')->required())
                            ->addActionLabel('Agregar paso')
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Evaluación de la observación de la actividad ejecutada')
                    ->description('Cada pregunta 1-11 vale 6,37% si la respuesta es Sí. El Sub-Total equivale al 70% del cumplimiento total.')
                    ->schema(self::preguntasChecklist()),

                Section::make('12. ¿Coinciden los pasos del procedimiento con la observación de la tarea generada?')
                    ->description('Esta pregunta equivale al 30% del cumplimiento total: 30% si coinciden, 0% si no.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('pasos_segun_procedimiento')
                            ->label('Pasos según procedimiento')
                            ->numeric()
                            ->minValue(0)
                            ->live(),

                        TextInput::make('pasos_en_observacion')
                            ->label('Pasos en la observación de la actividad ejecutada')
                            ->numeric()
                            ->minValue(0)
                            ->live(),

                        Placeholder::make('puntaje_preview')
                            ->label('Total % Cumplimiento (calculado al guardar)')
                            ->content(function ($get) {
                                $campos = AcompanamientoVerificacion::PREGUNTAS_CHECKLIST;
                                $marcadas = collect($campos)->filter(fn (string $c) => (bool) $get($c))->count();
                                $subTotal = round($marcadas * AcompanamientoVerificacion::VALOR_PREGUNTA, 2);

                                $segunProc = $get('pasos_segun_procedimiento');
                                $enObs = $get('pasos_en_observacion');
                                $coinciden = $segunProc !== null && $enObs !== null && (int) $segunProc === (int) $enObs;
                                $pregunta12 = ($segunProc !== null && $enObs !== null)
                                    ? ($coinciden ? AcompanamientoVerificacion::VALOR_PREGUNTA_12 : 0)
                                    : null;

                                $total = $pregunta12 !== null ? round($subTotal + $pregunta12, 2) : null;

                                return $total !== null
                                    ? "{$total}% (Sub-Total {$subTotal}% + Pregunta 12: {$pregunta12}%)"
                                    : "Sub-Total parcial: {$subTotal}% — complete ambos conteos de pasos para el total";
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Análisis de la actividad')
                    ->schema([
                        Textarea::make('hallazgos')
                            ->label('Oportunidades de mejora detectadas / observaciones')
                            ->rows(3)
                            ->columnSpanFull(),

                        Radio::make('analisis_actividad')
                            ->label('Conclusión')
                            ->options(collect(AnalisisActividad::cases())->mapWithKeys(fn ($c) => [$c->value => $c->label()]))
                            ->columnSpanFull(),

                        Textarea::make('plan_accion')
                            ->label('Plan de acción acordado')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Cierre')
                    ->columns(2)
                    ->schema([
                        Toggle::make('actividad_detenida')
                            ->label('Actividad detenida por riesgo crítico')
                            ->helperText('El observador tiene autoridad para detener la actividad independientemente del puntaje.')
                            ->live(),

                        Textarea::make('motivo_detencion')
                            ->label('Motivo de la detención')
                            ->rows(2)
                            ->visible(fn ($get) => $get('actividad_detenida'))
                            ->required(fn ($get) => $get('actividad_detenida')),
                    ]),
            ]);
    }
}
