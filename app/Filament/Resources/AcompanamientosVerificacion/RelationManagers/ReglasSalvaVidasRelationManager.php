<?php

namespace App\Filament\Resources\AcompanamientosVerificacion\RelationManagers;

use App\Enums\CumpleRegla;
use App\Enums\ReglaSalvaVidas;
use App\Models\AcompanamientoVerificacion;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ReglasSalvaVidasRelationManager extends RelationManager
{
    protected static string $relationship = 'reglasSalvaVidas';

    protected static ?string $title = 'Aplicación 12 Reglas que Salvan Vidas';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof AcompanamientoVerificacion && $ownerRecord->esInspeccionGerencial();
    }

    public function isReadOnly(): bool
    {
        // Las 12 reglas se editan también desde la página de Ver (no solo Editar):
        // es el flujo natural de diligenciar la Parte 2 del formato F-14.
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('cumple')
                    ->label('Cumple')
                    ->options([
                        CumpleRegla::Si->value => CumpleRegla::Si->label(),
                        CumpleRegla::No->value => CumpleRegla::No->label(),
                        CumpleRegla::Na->value => CumpleRegla::Na->label(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero_regla')
            ->defaultSort('numero_regla')
            ->columns([
                TextColumn::make('numero_regla')
                    ->label('Regla')
                    ->formatStateUsing(fn (?ReglaSalvaVidas $state): string => $state !== null
                        ? "{$state->value}. {$state->label()}"
                        : '—'),

                TextColumn::make('cumple')
                    ->label('Cumple')
                    ->badge()
                    ->formatStateUsing(fn (?CumpleRegla $state): string => $state?->label() ?? 'Sin evaluar')
                    ->color(fn (?CumpleRegla $state): string => $state?->color() ?? 'gray'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
