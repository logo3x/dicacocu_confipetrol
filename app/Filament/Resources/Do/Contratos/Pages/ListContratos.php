<?php

namespace App\Filament\Resources\Do\Contratos\Pages;

use App\Filament\Resources\Do\Contratos\ContratoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContratos extends ListRecords
{
    protected static string $resource = ContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuevo contrato'),
        ];
    }
}
