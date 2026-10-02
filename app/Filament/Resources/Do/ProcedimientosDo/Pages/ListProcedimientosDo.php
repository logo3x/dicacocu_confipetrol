<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Services\Do\ImportadorMatrizDo;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class ListProcedimientosDo extends ListRecords
{
    protected static string $resource = ProcedimientoDoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->accionImportar(),
            CreateAction::make()->label('Nuevo procedimiento'),
        ];
    }

    /**
     * Carga de la Matriz Integral. Se analiza primero y se aplica después,
     * para que nadie sobrescriba sin ver qué cambia.
     */
    private function accionImportar(): Action
    {
        return Action::make('importarMatriz')
            ->label('Importar matriz')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('gray')
            ->visible(fn (): bool => Auth::user()?->can('crear procedimientos do') ?? false)
            ->modalHeading('Importar Matriz Integral de Disciplina Operativa')
            ->modalDescription('Cargue el archivo HSEQ-GCA1-F-17. Verá qué se creará y qué cambiará antes de confirmar.')
            ->modalWidth(Width::ThreeExtraLarge)
            ->modalSubmitActionLabel('Analizar e importar')
            ->schema([
                FileUpload::make('archivo')
                    ->label('Archivo de la matriz')
                    ->required()
                    ->disk('local')
                    ->directory('importaciones')
                    ->visibility('private')
                    ->acceptedFileTypes([
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ])
                    ->maxSize(20480)
                    ->helperText('Formato .xlsx, máximo 20 MB.')
                    ->live(),

                Select::make('anio_ciclo')
                    ->label('Año del ciclo')
                    ->options(fn (): array => $this->aniosDisponibles())
                    ->default(now()->year)
                    ->required()
                    ->native(false),

                Toggle::make('crear_nuevos')
                    ->label('Crear los procedimientos que no existan')
                    ->default(true)
                    ->inline(false),

                Toggle::make('actualizar_existentes')
                    ->label('Actualizar los que ya existen con los datos de la matriz')
                    ->helperText('Solo se tocan los campos que la matriz trae con valor.')
                    ->default(false)
                    ->inline(false),

                Text::make(fn (Get $get): HtmlString => $this->resumen($get))
                    ->columnSpanFull(),
            ])
            ->action(fn (array $data) => $this->importar($data));
    }

    /** Vista previa de lo que haría la importación, antes de confirmarla. */
    private function resumen(Get $get): HtmlString
    {
        $ruta = $this->rutaDelArchivo($get('archivo'));

        if ($ruta === null) {
            return new HtmlString('<span style="color:#6e7785">Cargue el archivo para ver un resumen.</span>');
        }

        try {
            $analisis = app(ImportadorMatrizDo::class)->analizar($ruta, (int) ($get('anio_ciclo') ?: now()->year));
        } catch (\Throwable $e) {
            return new HtmlString(
                '<span style="color:#b91c1c">No se pudo leer el archivo: '.e($e->getMessage()).'</span>'
            );
        }

        $lineas = [
            $this->linea('Se crearán', $analisis['nuevos']->count(), '#047857'),
            $this->linea('Se actualizarán', $analisis['actualizables']->count(), '#b45309'),
            $this->linea('Sin cambios', $analisis['sin_cambios'], '#6e7785'),
        ];

        if ($analisis['problemas']->isNotEmpty()) {
            $detalle = $analisis['problemas']
                ->take(5)
                ->map(fn (array $p): string => '<li>Fila '.$p['fila'].': '.e($p['motivo']).'</li>')
                ->implode('');

            $restantes = $analisis['problemas']->count() - 5;

            $lineas[] = '<div style="margin-top:.75rem;color:#b91c1c">'
                .'<strong>'.$analisis['problemas']->count().' fila(s) no se podrán importar:</strong>'
                .'<ul style="margin:.375rem 0 0 1.25rem">'.$detalle
                .($restantes > 0 ? '<li>… y '.$restantes.' más.</li>' : '')
                .'</ul></div>';
        }

        return new HtmlString('<div style="font-size:.875rem;line-height:1.7">'.implode('', $lineas).'</div>');
    }

    private function linea(string $etiqueta, int $cantidad, string $color): string
    {
        return '<div><span style="color:'.$color.';font-weight:600">'.$cantidad.'</span> '.$etiqueta.'</div>';
    }

    /** @param  array<string, mixed>  $data */
    private function importar(array $data): void
    {
        $ruta = $this->rutaDelArchivo($data['archivo'] ?? null);

        if ($ruta === null) {
            Notification::make()
                ->title('No se encontró el archivo cargado')
                ->danger()
                ->send();

            return;
        }

        $importador = app(ImportadorMatrizDo::class);
        $anio = (int) $data['anio_ciclo'];

        $analisis = $importador->analizar($ruta, $anio);

        $filas = ($data['actualizar_existentes'] ?? false)
            ? $analisis['actualizables']->pluck('fila')->all()
            : [];

        $resultado = $importador->aplicar(
            $ruta,
            $anio,
            crearNuevos: (bool) ($data['crear_nuevos'] ?? true),
            filasAActualizar: $filas,
        );

        $cuerpo = $resultado['creados'].' creado(s), '.$resultado['actualizados'].' actualizado(s).';

        if ($analisis['problemas']->isNotEmpty()) {
            $cuerpo .= ' '.$analisis['problemas']->count().' fila(s) quedaron fuera por datos que no están en el catálogo.';
        }

        Notification::make()
            ->title('Matriz importada')
            ->body($cuerpo)
            ->success()
            ->send();
    }

    /** Ruta en disco del archivo que Filament acaba de subir. */
    private function rutaDelArchivo(mixed $archivo): ?string
    {
        $nombre = is_array($archivo) ? reset($archivo) : $archivo;

        if (blank($nombre)) {
            return null;
        }

        $ruta = Storage::disk('local')->path($nombre);

        return is_file($ruta) ? $ruta : null;
    }

    /** @return array<int, string> */
    private function aniosDisponibles(): array
    {
        $actual = now()->year;

        return collect(range($actual + 1, $actual - 3))
            ->mapWithKeys(fn (int $anio): array => [$anio => (string) $anio])
            ->all();
    }
}
