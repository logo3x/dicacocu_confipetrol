<?php

namespace App\Filament\Resources\Actividades\RelationManagers;

use App\Enums\EstadoSocializacion;
use App\Models\ActividadPersonalExpuesto;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonalExpuestoRelationManager extends RelationManager
{
    protected static string $relationship = 'personalExpuestoNominal';

    protected static ?string $title = 'Personal expuesto y socialización';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Persona expuesta')
                    ->relationship('persona', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user_id')
            ->columns([
                TextColumn::make('persona.name')
                    ->label('Persona')
                    ->searchable(),

                TextColumn::make('estado')
                    ->label('Estado de socialización')
                    ->badge()
                    ->state(fn (ActividadPersonalExpuesto $record) => $record->estado())
                    ->formatStateUsing(fn (EstadoSocializacion $state): string => $state->label())
                    ->color(fn (EstadoSocializacion $state): string => $state->color()),

                TextColumn::make('fecha_socializacion')
                    ->label('Socializado el')
                    ->date('d/m/Y')
                    ->placeholder('Pendiente'),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vence el')
                    ->date('d/m/Y')
                    ->placeholder('—'),

                TextColumn::make('socializadoPor.name')
                    ->label('Socializado por')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar persona expuesta'),
            ])
            ->recordActions([
                Action::make('socializar')
                    ->label('Socializar ahora')
                    ->icon('heroicon-o-megaphone')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ActividadPersonalExpuesto $record) => auth()->user()->can('socializar procedimiento')
                        && $record->estado() !== EstadoSocializacion::Vigente)
                    ->action(fn (ActividadPersonalExpuesto $record) => $record->socializar(auth()->user())),

                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
