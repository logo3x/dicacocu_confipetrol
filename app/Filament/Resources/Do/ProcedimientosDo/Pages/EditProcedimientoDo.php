<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use App\Filament\Resources\Do\ProcedimientosDo\Schemas\ProcedimientoDoForm;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProcedimientoDo extends EditRecord
{
    protected static string $resource = ProcedimientoDoResource::class;

    /** Etapa que se abrirá tras guardar con "Guardar y continuar". */
    public ?string $etapaDestino = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Guardar'),
            ...($this->siguienteEtapa() ? [$this->getGuardarYContinuarFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    /** Guarda los cambios y abre la etapa siguiente del procedimiento. */
    protected function getGuardarYContinuarFormAction(): Action
    {
        return Action::make('guardarYContinuar')
            ->label('Guardar y continuar')
            ->color('gray')
            ->keyBindings(['mod+shift+s'])
            // La pestaña visible vive en la URL del navegador, que no viaja en la
            // petición de Livewire; se envía como argumento de la acción.
            ->alpineClickHandler(
                "\$wire.guardarYContinuar(new URLSearchParams(window.location.search).get('tab'))"
            );
    }

    public function guardarYContinuar(?string $tabActual = null): void
    {
        $this->etapaDestino = ProcedimientoDoForm::siguienteEtapa($tabActual);

        $this->save();
    }

    protected function getRedirectUrl(): ?string
    {
        if (! $this->etapaDestino) {
            return null;
        }

        return $this->getResource()::getUrl('edit', [
            'record' => $this->getRecord(),
            'tab' => $this->etapaDestino,
        ]);
    }

    private function siguienteEtapa(): ?string
    {
        return ProcedimientoDoForm::siguienteEtapa(request()->query('tab'));
    }
}
