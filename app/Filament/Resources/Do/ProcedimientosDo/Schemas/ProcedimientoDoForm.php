<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Schemas;

use App\Enums\Do\CriterioAmenaza;
use App\Enums\Do\CriterioOpt;
use App\Models\Do\Campo;
use App\Models\Documento;
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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProcedimientoDoForm
{
    /** Etiquetas de las pestañas; Filament deriva de ellas el identificador de la URL. */
    public const ETAPA_DI = '1 · DI';

    public const ETAPA_CA = '2 · CA';

    public const ETAPA_CO = '3 · CO';

    public const ETAPA_CU = '4 · CU';

    /** Orden de las etapas, para avanzar a la siguiente al guardar. */
    public const ORDEN_ETAPAS = [self::ETAPA_DI, self::ETAPA_CA, self::ETAPA_CO, self::ETAPA_CU];

    /**
     * Identificador con el que Filament persiste una pestaña en la URL: el slug
     * de la etiqueta más la ruta de estado del formulario.
     */
    public static function idEtapa(string $etiqueta): string
    {
        return Str::slug(Str::transliterate($etiqueta, strict: true)).'::data::tab';
    }

    /** Siguiente etapa a abrir, o null si ya se está en la última. */
    public static function siguienteEtapa(?string $etapaActual): ?string
    {
        $ids = array_map(self::idEtapa(...), self::ORDEN_ETAPAS);

        $indice = array_search($etapaActual, $ids, true);

        if ($indice === false) {
            return $ids[1];
        }

        return $ids[$indice + 1] ?? null;
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
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
            ->label(self::ETAPA_DI)
            ->icon('heroicon-o-clipboard-document-list')
            ->schema([
                Section::make('Inventario de actividades')
                    ->columns(3)
                    ->schema([
                        TextInput::make('anio_ciclo')
                            ->label('Año del ciclo')
                            ->numeric()
                            ->required()
                            ->default(now()->year)
                            ->minValue(2000)
                            ->maxValue(2100),
                        Select::make('contrato_id')
                            ->label('Contrato')
                            // Quien no es administrador solo registra procedimientos de su contrato.
                            ->relationship('contrato', 'nombre', function ($query) {
                                $usuario = auth()->user();

                                return $usuario?->veTodosLosContratos()
                                    ? $query->activos()
                                    : $query->activos()->whereKey($usuario?->contrato_id);
                            })
                            ->default(fn () => auth()->user()?->veTodosLosContratos()
                                ? null
                                : auth()->user()?->contrato_id)
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('campo_id', null))
                            ->helperText('Se administra desde Contratos'),
                        Select::make('campo_id')
                            ->label('Campo')
                            ->options(fn (Get $get) => $get('contrato_id')
                                ? Campo::query()
                                    ->activos()
                                    ->where('contrato_id', $get('contrato_id'))
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                                : [])
                            ->required()
                            ->searchable()
                            ->disabled(fn (Get $get): bool => blank($get('contrato_id')))
                            ->helperText('Seleccione primero el contrato'),
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
                            ->live(onBlur: true)
                            // Propone el mismo valor en la Etapa 3 mientras el usuario no lo haya ajustado.
                            ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                if (blank($get('personas_socializadas'))) {
                                    $set('personas_socializadas', $state);
                                }
                            }),
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
            ->label(self::ETAPA_CA)
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
                        Select::make('documento_id')
                            ->label('Documento del repositorio')
                            ->options(fn () => self::documentosDisponibles())
                            ->searchable()
                            ->preload()
                            ->placeholder('(aún no está en el repositorio)')
                            ->helperText('Al elegirlo se completan el código, el título y la versión.')
                            ->live()
                            ->afterStateUpdated(self::copiarDatosDelDocumento(...))
                            ->columnSpan(2),
                        TextInput::make('ubicacion_acceso')
                            ->label('Ubicación o acceso')
                            ->maxLength(191)
                            ->helperText('Úsela solo si el procedimiento no está en el repositorio.')
                            ->columnSpan(2),
                    ]),
            ]);
    }

    /** Etapa 3 — Comunicación. */
    private static function etapaCo(): Tab
    {
        return Tab::make('Etapa 3 · CO')
            ->label(self::ETAPA_CO)
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
                            ->helperText(fn (Get $get): string => 'Sugerido: '.((int) $get('personas_involucradas'))
                                .' personas involucradas (Etapa 1). Puede ajustarlo.')
                            ->placeholder(fn (Get $get): string => (string) ((int) $get('personas_involucradas')))
                            ->maxValue(fn (Get $get) => (int) $get('personas_involucradas'))
                            ->validationMessages([
                                'max' => 'No puede socializarse a más personas de las involucradas en la actividad.',
                            ]),
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
            ->label(self::ETAPA_CU)
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
                            // Al recargar el formulario el estado puede llegar como texto.
                            ->formatStateUsing(fn ($state) => match (true) {
                                $state instanceof CriterioOpt => $state->label(),
                                filled($state) => CriterioOpt::tryFrom($state)?->label(),
                                default => null,
                            }),
                    ])
                    ->visibleOn('edit'),
            ]);
    }

    /**
     * Documentos del repositorio que ya pasaron por aprobación: son los
     * únicos a los que tiene sentido apuntar desde la matriz.
     *
     * @return array<int, string>
     */
    private static function documentosDisponibles(): array
    {
        return Documento::query()
            ->whereIn('estado', ['aprobado', 'divulgado', 'verificado'])
            ->orderBy('codigo')
            ->orderBy('titulo')
            ->get()
            ->mapWithKeys(fn (Documento $documento): array => [
                $documento->getKey() => trim(($documento->codigo ? $documento->codigo.' — ' : '').$documento->titulo),
            ])
            ->all();
    }

    /**
     * Al enlazar un documento se traen su código, título y versión, para no
     * escribirlos otra vez ni arriesgar que queden distintos.
     */
    private static function copiarDatosDelDocumento(?string $state, Set $set): void
    {
        if (blank($state)) {
            return;
        }

        $documento = Documento::find($state);

        if (! $documento) {
            return;
        }

        $set('codigo_asignado', $documento->codigo);
        $set('titulo_procedimiento', $documento->titulo);
        $set('version_actual', $documento->version_actual);
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
