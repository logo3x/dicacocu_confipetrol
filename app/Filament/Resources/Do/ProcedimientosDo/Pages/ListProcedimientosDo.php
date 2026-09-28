<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProcedimientosDo extends ListRecords
{
    protected static string $resource = ProcedimientoDoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuevo procedimiento'),
        ];
    }
}
