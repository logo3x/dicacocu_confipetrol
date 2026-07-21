<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\RelationManagers;

use App\Models\AcompanamientoVerificacion;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AccionesInspeccionRelationManager extends RelationManager
{
    protected static string $relationship = 'accionesInspeccion';

    protected static ?string $title = 'Acciones definidas y acordadas';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof AcompanamientoVerificacion && $ownerRecord->esInspeccionGerencial();
    }

    public function isReadOnly(): bool
    {
        // Las acciones definidas y acordadas se gestionan también desde la página de
        // Ver (no solo Editar): es el flujo natural de diligenciar la Parte 2 del F-14.
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('accion')
                    ->label('Acción')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),

                Select::make('responsable_id')
                    ->label('Responsable')
                    ->relationship('responsable', 'name')
                    ->searchable()
                    ->preload(),

                DatePicker::make('fecha_cierre')
                    ->label('Fecha de cierre'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('accion')
            ->columns([
                TextColumn::make('accion')
                    ->label('Acción')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->accion),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->placeholder('Sin asignar'),

                TextColumn::make('fecha_cierre')
                    ->label('Fecha de cierre')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['inspeccion_gerencial_id'] = $this->getOwnerRecord()->inspeccionGerencial->id;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
