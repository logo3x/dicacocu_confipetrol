<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Filament\Resources\Documentos\RelationManagers\VersionesRelationManager;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDocumento extends EditRecord
{
    protected static string $resource = DocumentoResource::class;

    public function getRelationManagers(): array
    {
        return [
            VersionesRelationManager::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('enviar_revision')
                ->label('Enviar a revisión')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado === 'borrador')
                ->action(function (): void {
                    $this->record->update(['estado' => 'en_revision']);
                    Notification::make()
                        ->success()
                        ->title('Documento enviado a revisión')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),

            Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado === 'en_revision')
                ->action(function (): void {
                    $this->record->update([
                        'estado' => 'aprobado',
                        'aprobador_id' => auth()->id(),
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Documento aprobado')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),

            Action::make('rechazar')
                ->label('Rechazar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Rechazar documento')
                ->modalDescription('El documento volverá a estado borrador.')
                ->visible(fn () => $this->record->estado === 'en_revision')
                ->action(function (): void {
                    $this->record->update(['estado' => 'rechazado']);
                    Notification::make()
                        ->warning()
                        ->title('Documento rechazado')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),

            Action::make('divulgar')
                ->label('Divulgar')
                ->icon('heroicon-o-megaphone')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->estado === 'aprobado')
                ->action(function (): void {
                    $this->record->update(['estado' => 'divulgado']);
                    Notification::make()
                        ->success()
                        ->title('Documento divulgado')
                        ->send();
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),

            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
