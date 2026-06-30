<?php

namespace App\Filament\Resources\Carpetas\Pages;

use App\Filament\Resources\Carpetas\CarpetaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCarpeta extends EditRecord
{
    protected static string $resource = CarpetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
