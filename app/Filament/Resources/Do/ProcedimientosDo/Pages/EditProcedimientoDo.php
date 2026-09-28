<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProcedimientoDo extends EditRecord
{
    protected static string $resource = ProcedimientoDoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
