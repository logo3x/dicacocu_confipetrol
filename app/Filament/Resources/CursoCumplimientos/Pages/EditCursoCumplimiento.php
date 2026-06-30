<?php

namespace App\Filament\Resources\CursoCumplimientos\Pages;

use App\Filament\Resources\CursoCumplimientos\CursoCumplimientoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCursoCumplimiento extends EditRecord
{
    protected static string $resource = CursoCumplimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
