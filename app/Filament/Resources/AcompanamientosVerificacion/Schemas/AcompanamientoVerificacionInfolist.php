<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\Schemas;

use App\Enums\AnalisisActividad;
use App\Enums\ClasificacionOpt;
use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use App\Models\AcompanamientoVerificacion;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class AcompanamientoVerificacionInfolist
{
    private static function preguntasChecklist(): array
    {
        $preguntas = [
            'q1_procedimiento_disponible' => '1. Procedimiento disponible y de fácil acceso',
            'q2_usa_epp_correctamente' => '2. Usa correctamente los EPP / procedimiento de trabajo',
            'q3_identifica_peligros_riesgos' => '3. Identifica peligros y riesgos (AST, APR, IPERC cont.)',
            'q4_herramientas_disponibles' => '4. Herramientas disponibles y usadas correctamente',
            'q5_area_limpia_ordenada' => '5. Área de trabajo limpia y ordenada',
            'q6_aplica_controles' => '6. Aplica los controles necesarios',
            'q7_procedimiento_actualizado' => '7. Procedimiento actualizado, codificado y en buen estado',
            'q8_procedimiento_facil_entendimiento' => '8. Procedimiento de fácil entendimiento',
            'q9_procedimiento_divulgado' => '9. Procedimiento divulgado al personal',
            'q10_personal_capacitado_certificado' => '10. Personal capacitado y certificado',
            'q11_personal_mostro_habilidad' => '11. Personal mostró habilidad durante la ejecución',
        ];

        return collect($preguntas)
            ->map(fn (string $label, string $campo) => IconEntry::make($campo)->label($label)->boolean())
            ->values()
            ->all();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación del acompañamiento')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('actividad.nombre')
                            ->label('Actividad observada')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),

                        TextEntry::make('fecha_ejecucion')
                            ->label('Fecha de ejecución')
                            ->date('d/m/Y'),

                        TextEntry::make('tipo_verificacion')
                            ->label('Tipo de verificación')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'verificacion_cumplimiento_do' => 'Verificación Cumplimiento DO',
                                'inspeccion_gerencial_caminar_planta' => 'Inspección Gerencial — Caminar la Planta',
                                default => $state,
                            }),

                        TextEntry::make('area')
                            ->label('Área')
                            ->placeholder('—'),

                        TextEntry::make('campo')
                            ->label('Campo')
                            ->placeholder('—'),

                        TextEntry::make('responsableArea.name')
                            ->label('Responsable del área')
                            ->placeholder('Sin asignar'),
                    ]),

                Section::make('Quién observa y/o visita')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('observador.name')
                            ->label('Observador'),

                        TextEntry::make('cargo_observador')
                            ->label('Cargo')
                            ->placeholder('—'),

                        TextEntry::make('acompanante.name')
                            ->label('Acompañante')
                            ->placeholder('—'),

                        TextEntry::make('cargo_acompanante')
                            ->label('Cargo')
                            ->placeholder('—'),
                    ]),

                Section::make('Observación de la actividad ejecutada')
                    ->schema([
                        RepeatableEntry::make('pasos_observados')
                            ->label('Paso a paso observado')
                            ->schema([
                                TextEntry::make('paso')->label(''),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Evaluación de la observación (Sub-Total = 70% del cumplimiento)')
                    ->columns(2)
                    ->schema(self::preguntasChecklist()),

                Section::make('12. Coincidencia de pasos (30% del cumplimiento)')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pasos_segun_procedimiento')
                            ->label('Pasos según procedimiento')
                            ->placeholder('—'),

                        TextEntry::make('pasos_en_observacion')
                            ->label('Pasos en la observación')
                            ->placeholder('—'),

                        TextEntry::make('coinciden')
                            ->label('¿Coinciden?')
                            ->state(fn (AcompanamientoVerificacion $record) => match ($record->coincidenPasos()) {
                                true => 'Sí',
                                false => 'No',
                                null => 'Pendiente',
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Análisis de la actividad')
                    ->schema([
                        TextEntry::make('hallazgos')
                            ->label('Oportunidades de mejora detectadas')
                            ->placeholder('Sin hallazgos registrados')
                            ->columnSpanFull(),

                        TextEntry::make('analisis_actividad')
                            ->label('Conclusión')
                            ->badge()
                            ->formatStateUsing(fn (?AnalisisActividad $state): string => $state?->label() ?? 'Sin definir')
                            ->color(fn (?AnalisisActividad $state): string => $state?->color() ?? 'gray')
                            ->columnSpanFull(),

                        TextEntry::make('plan_accion')
                            ->label('Plan de acción acordado')
                            ->placeholder('No requiere plan de acción')
                            ->columnSpanFull(),
                    ]),

                Section::make('Resultado y cierre')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('puntaje_opt_calculado')
                            ->label('Total % Cumplimiento')
                            ->suffix('%')
                            ->placeholder('Sin evaluar'),

                        TextEntry::make('clasificacion_opt')
                            ->label('Clasificación')
                            ->badge()
                            ->formatStateUsing(fn (?ClasificacionOpt $state): string => $state?->label() ?? 'Sin evaluar')
                            ->color(fn (?ClasificacionOpt $state): string => $state?->color() ?? 'gray'),

                        TextEntry::make('clasificacion_opt')
                            ->label('Estado')
                            ->formatStateUsing(fn (?ClasificacionOpt $state): string => $state?->estado() ?? 'Pendiente de evaluar')
                            ->columnSpanFull(),

                        IconEntry::make('actividad_detenida')
                            ->label('¿Actividad detenida por riesgo crítico?')
                            ->boolean()
                            ->trueColor('danger'),

                        TextEntry::make('motivo_detencion')
                            ->label('Motivo de detención')
                            ->placeholder('—')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->actividad_detenida),
                    ]),

                Section::make('Parte 2 — Inspección Gerencial Caminar la Planta')
                    ->visible(fn (AcompanamientoVerificacion $record) => $record->esInspeccionGerencial())
                    ->schema([
                        RepeatableEntry::make('inspeccionGerencial.reglas')
                            ->label('Aplicación 12 Reglas que Salvan Vidas')
                            ->schema([
                                TextEntry::make('numero_regla')
                                    ->label('Regla')
                                    ->formatStateUsing(fn (?ReglaSalvaVidas $state): string => $state?->label() ?? '—'),

                                TextEntry::make('cumple')
                                    ->label('Cumple')
                                    ->badge()
                                    ->formatStateUsing(fn (?CumpleRegla $state): string => $state?->label() ?? 'Sin evaluar')
                                    ->color(fn (?CumpleRegla $state): string => $state?->color() ?? 'gray'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),

                        TextEntry::make('inspeccionGerencial.hallazgos_positivos')
                            ->label('Hallazgos positivos')
                            ->placeholder('—'),

                        TextEntry::make('inspeccionGerencial.desvios_oportunidades_mejora')
                            ->label('Desvíos / oportunidades de mejora')
                            ->placeholder('—'),

                        RepeatableEntry::make('inspeccionGerencial.acciones')
                            ->label('Acciones definidas y acordadas')
                            ->schema([
                                TextEntry::make('accion')->label('Acción'),
                                TextEntry::make('responsable.name')->label('Responsable')->placeholder('—'),
                                TextEntry::make('fecha_cierre')->label('Fecha de cierre')->date('d/m/Y')->placeholder('—'),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
