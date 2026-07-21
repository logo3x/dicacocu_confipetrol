<?php

namespace App\Filament\Resources\Compromisos\Pages;

use App\Filament\Resources\Compromisos\CompromisoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompromisos extends ListRecords
{
    protected static string $resource = CompromisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
