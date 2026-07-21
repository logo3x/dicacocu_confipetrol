<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\Pages;

use App\Filament\Resources\AcompanamientosVerificacion\AcompanamientoVerificacionResource;
use App\Filament\Resources\AcompanamientosVerificacion\Schemas\AcompanamientoVerificacionInfolist;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewAcompanamientoVerificacion extends ViewRecord
{
    protected static string $resource = AcompanamientoVerificacionResource::class;

    public function infolist(Schema $schema): Schema
    {
        return AcompanamientoVerificacionInfolist::configure($schema);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editarParte2')
                ->label('Editar Parte 2 — Hallazgos')
                ->icon('heroicon-o-clipboard-document-list')
                ->visible(fn () => $this->record->esInspeccionGerencial())
                ->schema([
                    Textarea::make('hallazgos_positivos')
                        ->label('Hallazgos positivos')
                        ->rows(4),

                    Textarea::make('desvios_oportunidades_mejora')
                        ->label('Desvíos / oportunidades de mejora')
                        ->rows(4),
                ])
                ->fillForm(fn () => $this->record->inspeccionGerencial?->only([
                    'hallazgos_positivos',
                    'desvios_oportunidades_mejora',
                ]) ?? [])
                ->action(function (array $data): void {
                    $this->record->inspeccionGerencial?->update($data);
                }),

            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
