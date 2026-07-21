<?php

namespace App\Filament\Resources\Actividades\Pages;

use App\Filament\Resources\Actividades\ActividadResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditActividad extends EditRecord
{
    protected static string $resource = ActividadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
