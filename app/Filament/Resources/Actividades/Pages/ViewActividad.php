<?php

namespace App\Filament\Resources\Actividades\Pages;

use App\Filament\Resources\Actividades\ActividadResource;
use App\Filament\Resources\Actividades\Schemas\ActividadInfolist;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewActividad extends ViewRecord
{
    protected static string $resource = ActividadResource::class;

    public function infolist(Schema $schema): Schema
    {
        return ActividadInfolist::configure($schema);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
