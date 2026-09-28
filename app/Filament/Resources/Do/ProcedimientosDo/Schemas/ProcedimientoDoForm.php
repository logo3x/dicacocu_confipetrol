<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Schemas;

use App\Enums\Do\CriterioAmenaza;
use App\Models\User;
use App\Services\Do\CalculadoraDo;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProcedimientoDoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identificación del registro')
                ->columns(3)
                ->schema([
                    TextInput::make('anio_ciclo')
                        ->label('Año del ciclo')
                        ->numeric()
                        ->required()
                        ->default(now()->year)
                        ->minValue(2000)
                        ->maxValue(2100),
                    TextInput::make('contrato')->label('Contrato')->required()->maxLength(191),
                    TextInput::make('campo')->label('Campo')->required()->maxLength(191),
                ]),

            Tabs::make('Etapas')
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs([
                    self::etapaDi(),
                    self::etapaCa(),
                    self::etapaCo(),
                    self::etapaCu(),
                ]),
        ]);
    }

    /** Etapa 1 — Identificación y priorización de actividades. */
    private static function etapaDi(): Tab
    {
        return Tab::make('Etapa 1 · DI')
            ->label('Etapa 1 · DI — Identificación')
            ->icon('heroicon-o-clipboard-document-list')
            ->schema([
                Section::make('Inventario de actividades')
                    ->columns(3)
                    ->schema([
                        TextInput::make('nombre_actividad')
                            ->label('Nombre de la actividad')
                            ->required()
                            ->maxLength(191)
                            ->columnSpan(2),
                        DatePicker::make('fecha_identificacion')
                            ->label('Fecha de identificación')
                            ->required()
                            ->default(now())
                            ->live(),
                        TextInput::make('personas_involucradas')
                            ->label('N° de personas involucradas en la actividad')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->live(onBlur: true),
                    ]),

                Section::make('Valoración de amenaza')
                    ->description('Marque los criterios que representan una amenaza. El puntaje de cada criterio se suma para determinar la prioridad.')
                    ->schema([
                        ...array_map(
                            fn (CriterioAmenaza $criterio) => Toggle::make($criterio->value)
                                ->label($criterio->label().' ('.$criterio->peso().')')
                                ->live()
                                ->default(false),
                            CriterioAmenaza::cases(),
                        ),

                        Grid::make(2)->schema([
                            Text::make(fn (Get $get): string => 'Puntaje de prioridad: '.self::puntaje($get).' / 100')
                                ->weight('bold'),
                            Text::make(fn (Get $get): string => 'Prioridad: '.CalculadoraDo::prioridad(self::puntaje($get))->label())
                                ->color(fn (Get $get): string => CalculadoraDo::prioridad(self::puntaje($get))->color())
                                ->weight('bold'),
                        ]),
                    ]),
            ]);
    }

    /** Etapa 2 — Plan de estandarización y codificación. */
    private static function etapaCa(): Tab
    {
        return Tab::make('Etapa 2 · CA')
            ->label('Etapa 2 · CA — Estandarización')
            ->icon('heroicon-o-document-check')
            ->schema([
                Section::make('Plazo de estandarización')
                    ->description('El tiempo máximo se deriva automáticamente de la prioridad calculada en la Etapa 1.')
                    ->schema([
                        Grid::make(2)->schema([
                            Text::make(fn (Get $get): string => 'Tiempo máximo de estandarización: '
                                .CalculadoraDo::prioridad(self::puntaje($get))->etiquetaPlazo())
                                ->weight('bold'),
                            Text::make(function (Get $get): string {
                                $fecha = $get('fecha_identificacion');

                                if (! $fecha) {
                                    return 'Fecha límite: registre la fecha de identificación';
                                }

                                $meses = CalculadoraDo::prioridad(self::puntaje($get))->plazoEstandarizacionMeses();

                                return 'Fecha límite: '.Carbon::parse($fecha)->addMonths($meses)->format('d/m/Y');
                            }),
                        ]),
                    ]),

                Section::make('Codificación')
                    ->columns(3)
                    ->schema([
                        Toggle::make('codificado')->label('¿Codificado?')->live(),
                        DatePicker::make('fecha_programada_codificacion')
                            ->label('Fecha programada')
                            ->helperText('Deje vacío si el documento ya estaba estandarizado (N.A.)'),
                        DatePicker::make('fecha_codificacion')->label('Fecha de codificación'),
                        Select::make('responsable_codificacion_id')
                            ->label('Responsable de la codificación')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        TextInput::make('codigo_asignado')
                            ->label('Código asignado')
                            ->maxLength(191)
                            ->unique(ignoreRecord: true),
                        TextInput::make('version_actual')
                            ->label('Versión actual')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(15)
                            ->helperText('De 1 a 15'),
                        TextInput::make('titulo_procedimiento')
                            ->label('Título del procedimiento / instructivo')
                            ->maxLength(191)
                            ->columnSpan(2),
                        TextInput::make('ubicacion_acceso')
                            ->label('Ubicación o acceso al procedimiento')
                            ->maxLength(191),
                    ]),
            ]);
    }

    /** Etapa 3 — Comunicación. */
    private static function etapaCo(): Tab
    {
        return Tab::make('Etapa 3 · CO')
            ->label('Etapa 3 · CO — Comunicación')
            ->icon('heroicon-o-megaphone')
            ->schema([
                Section::make('Divulgación del procedimiento')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('fecha_ultima_divulgacion')
                            ->label('Fecha de última divulgación'),
                        TextInput::make('personas_socializadas')
                            ->label('N° de personas a las que se socializó')
                            ->numeric()
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->helperText('Toma por defecto el valor de la Etapa 1, pero es editable')
                            ->default(fn (Get $get) => $get('personas_involucradas')),
                        Text::make(function (Get $get): string {
                            $cobertura = CalculadoraDo::coberturaSocializacion(
                                (int) $get('personas_socializadas'),
                                (int) $get('personas_involucradas'),
                            );

                            return "% promedio de cobertura: {$cobertura}%";
                        })->weight('bold'),
                    ]),
            ]);
    }

    /** Etapa 4 — Programa de verificación de Disciplina Operativa. */
    private static function etapaCu(): Tab
    {
        return Tab::make('Etapa 4 · CU')
            ->label('Etapa 4 · CU — Verificación')
            ->icon('heroicon-o-shield-check')
            ->schema([
                Section::make('Programa de verificación')
                    ->description('La frecuencia se deriva de la prioridad calculada en la Etapa 1.')
                    ->columns(3)
                    ->schema([
                        Text::make(fn (Get $get): string => 'Frecuencia de verificación: '
                            .CalculadoraDo::prioridad(self::puntaje($get))->etiquetaFrecuencia())
                            ->weight('bold')
                            ->columnSpanFull(),
                        Select::make('responsable_area_id')
                            ->label('Responsable del área')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        DatePicker::make('fecha_programada_verificacion')->label('Fecha programada'),
                        DatePicker::make('fecha_ejecutada_verificacion')
                            ->label('Fecha ejecutada')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Se actualiza con la última evaluación F-14 registrada'),
                        Select::make('observador_operativo_id')
                            ->label('Observador — Responsable operativo')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        Select::make('observador_hseq_id')
                            ->label('Observador — Responsable HSEQ')
                            ->options(fn () => User::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Resultados de verificación')
                    ->description('El puntaje OPT es el promedio de las evaluaciones F-14 registradas. Use el botón "Nuevo" del listado de evaluaciones para diligenciar el formato.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('puntaje_opt')
                            ->label('Puntaje OPT')
                            ->disabled()
                            ->dehydrated(false)
                            ->suffix('%'),
                        TextInput::make('criterio_opt')
                            ->label('Criterio de aprobación OPT')
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn ($state) => $state?->label()),
                    ])
                    ->visibleOn('edit'),
            ]);
    }

    /** Puntaje de prioridad en vivo a partir del estado del formulario. */
    private static function puntaje(Get $get): int
    {
        $criterios = [];

        foreach (CriterioAmenaza::cases() as $criterio) {
            $criterios[$criterio->value] = (bool) $get($criterio->value);
        }

        return CalculadoraDo::puntajePrioridad($criterios);
    }
}
