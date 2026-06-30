<?php

namespace App\Filament\Resources\Documentos\Pages;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Filament\Resources\Documentos\RelationManagers\VersionesRelationManager;
use App\Filament\Resources\Documentos\Schemas\DocumentoInfolist;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewDocumento extends ViewRecord
{
    protected static string $resource = DocumentoResource::class;

    public function infolist(Schema $schema): Schema
    {
        return DocumentoInfolist::configure($schema);
    }

    public function getRelationManagers(): array
    {
        return [
            VersionesRelationManager::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
