<?php

namespace App\Filament\Pages;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Primer ingreso: el usuario indica a qué contrato y campo pertenece, lo que
 * determina los procedimientos que verá.
 */
class SeleccionarContrato extends Page
{
    protected string $view = 'filament.pages.seleccionar-contrato';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $slug = 'seleccionar-contrato';

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function getTitle(): string|Htmlable
    {
        return 'Indique su contrato';
    }

    public function mount(): void
    {
        if (auth()->user()?->contrato_id) {
            $this->redirect(static::getUrl('index', panel: 'admin'), navigate: false);

            return;
        }

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Su asignación')
                    ->description('Seleccione el contrato en el que trabaja. Con esto verá los procedimientos que le corresponden.')
                    ->columns(2)
                    ->schema([
                        Select::make('contrato_id')
                            ->label('Contrato')
                            ->options(fn () => Contrato::query()
                                ->activos()
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id')
                                ->all())
                            ->required()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('campo_id', null)),

                        Select::make('campo_id')
                            ->label('Campo o estación')
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
                    ]),
            ]);
    }

    public function guardar(): void
    {
        $datos = $this->form->getState();

        $usuario = auth()->user();

        $campo = Campo::query()
            ->where('contrato_id', $datos['contrato_id'])
            ->findOrFail($datos['campo_id']);

        $usuario->update([
            'contrato_id' => $campo->contrato_id,
            'campo_id' => $campo->id,
        ]);

        Notification::make()
            ->title('Asignación guardada')
            ->body('Ya puede consultar los procedimientos de su contrato.')
            ->success()
            ->send();

        $this->redirect(filament()->getPanel('admin')->getUrl(), navigate: false);
    }

    /** @return array<Action> */
    protected function getFormActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar y continuar')
                ->submit('guardar'),
        ];
    }
}
