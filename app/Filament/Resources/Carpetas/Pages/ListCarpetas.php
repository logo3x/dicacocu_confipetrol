<?php

namespace App\Filament\Resources\Carpetas\Pages;

use App\Filament\Resources\Carpetas\CarpetaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCarpetas extends ListRecords
{
    protected static string $resource = CarpetaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
