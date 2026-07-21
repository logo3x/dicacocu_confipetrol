<?php

namespace App\Filament\Resources\Compromisos\Pages;

use App\Filament\Resources\Compromisos\CompromisoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCompromiso extends EditRecord
{
    protected static string $resource = CompromisoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
