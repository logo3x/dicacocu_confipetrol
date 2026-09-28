<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\Pages;

use App\Filament\Resources\Do\ProcedimientosDo\ProcedimientoDoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProcedimientoDo extends CreateRecord
{
    protected static string $resource = ProcedimientoDoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
