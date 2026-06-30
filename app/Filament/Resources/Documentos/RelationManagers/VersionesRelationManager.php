<?php

namespace App\Filament\Resources\Documentos\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VersionesRelationManager extends RelationManager
{
    protected static string $relationship = 'versiones';

    protected static ?string $title = 'Historial de versiones';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('version')
                    ->label('Número de versión')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'borrador' => 'Borrador',
                        'en_revision' => 'En revisión',
                        'aprobado' => 'Aprobado',
                        'rechazado' => 'Rechazado',
                    ])
                    ->default('borrador')
                    ->required(),

                Textarea::make('cambios')
                    ->label('Descripción de cambios')
                    ->rows(3)
                    ->columnSpanFull(),

                Textarea::make('motivo_rechazo')
                    ->label('Motivo de rechazo')
                    ->rows(2)
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('estado') === 'rechazado'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version')
            ->defaultSort('version', 'desc')
            ->columns([
                TextColumn::make('version')
                    ->label('Versión')
                    ->badge()
                    ->color('primary')
                    ->prefix('v')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'gray',
                        'en_revision' => 'warning',
                        'aprobado' => 'success',
                        'rechazado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('cambios')
                    ->label('Cambios')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->cambios),

                TextColumn::make('creador.name')
                    ->label('Creado por'),

                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                CreateAction::make()
                    ->label('Nueva versión')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();

                        return $data;
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->infolist(fn (Schema $schema): Schema => $schema->components([
                        TextEntry::make('version')->prefix('v')->label('Versión'),
                        TextEntry::make('estado')->label('Estado')->badge(),
                        TextEntry::make('cambios')->label('Descripción de cambios')->columnSpanFull(),
                        TextEntry::make('motivo_rechazo')->label('Motivo de rechazo')->columnSpanFull(),
                        TextEntry::make('creador.name')->label('Creado por'),
                        TextEntry::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
                    ])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
