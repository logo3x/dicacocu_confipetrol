<?php

namespace App\Filament\Resources\CursoCumplimientos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CursoCumplimientosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->titulo),

                TextColumn::make('fase_dicacocu')
                    ->label('Fase')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'inactivo' => 'gray',
                        'borrador' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                TextColumn::make('nota_aprobacion')
                    ->label('Aprobación')
                    ->suffix('%')
                    ->alignCenter(),

                TextColumn::make('inscripciones_count')
                    ->label('Inscritos')
                    ->counts('inscripciones')
                    ->alignCenter(),

                IconColumn::make('certificado_activo')
                    ->label('Certif.')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('fecha_limite')
                    ->label('Fecha límite')
                    ->date('d/m/Y')
                    ->placeholder('Sin límite')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'inactivo' => 'Inactivo',
                        'borrador' => 'Borrador',
                    ]),

                SelectFilter::make('fase_dicacocu')
                    ->label('Fase DICACOCU')
                    ->options([
                        'D' => 'D — Disponibilidad',
                        'I' => 'I — Integridad',
                        'C' => 'C — Calidad',
                        'A' => 'A — Acceso',
                        'O' => 'O — Operación',
                        'U' => 'U — Uso',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
