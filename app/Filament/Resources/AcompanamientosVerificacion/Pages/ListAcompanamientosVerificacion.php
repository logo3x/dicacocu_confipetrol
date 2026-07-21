<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\Pages;

use App\Filament\Resources\AcompanamientosVerificacion\AcompanamientoVerificacionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcompanamientosVerificacion extends ListRecords
{
    protected static string $resource = AcompanamientoVerificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
