<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Filament\Resources\Do\ProcedimientosDo\Schemas\ProcedimientoDoForm;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedimientoDo extends CreateRecord
{
    protected static string $resource = ProcedimientoDoResource::class;

    /** Etapa que se abrirá tras guardar con "Crear y continuar". */
    public ?string $siguienteEtapa = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', array_filter([
            'record' => $this->getRecord(),
            'tab' => $this->siguienteEtapa,
        ]));
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Crear'),
            $this->getCrearYContinuarFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    /**
     * Guarda el procedimiento y continúa en la Etapa 2, para completar las
     * cuatro etapas sin perder lo capturado en la primera.
     */
    protected function getCrearYContinuarFormAction(): Action
    {
        return Action::make('crearYContinuar')
            ->label('Crear y continuar')
            ->color('gray')
            ->keyBindings(['mod+shift+s'])
            ->action('crearYContinuar');
    }

    public function crearYContinuar(): void
    {
        $this->siguienteEtapa = ProcedimientoDoForm::idEtapa(ProcedimientoDoForm::ETAPA_CA);

        $this->create();
    }
}
