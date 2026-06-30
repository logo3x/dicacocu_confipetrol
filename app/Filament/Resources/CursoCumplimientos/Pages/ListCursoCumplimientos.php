<?php

namespace App\Filament\Resources\CursoCumplimientos\Pages;

use App\Filament\Resources\CursoCumplimientos\CursoCumplimientoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCursoCumplimientos extends ListRecords
{
    protected static string $resource = CursoCumplimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
