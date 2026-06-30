<?php

namespace App\Filament\Resources\Documentos\Tables;

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

class DocumentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->copyable(),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->titulo),

                TextColumn::make('tipo_documento')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'procedimiento' => 'primary',
                        'instructivo'   => 'info',
                        'formato'       => 'gray',
                        'manual'        => 'warning',
                        'politica'      => 'danger',
                        default         => 'gray',
                    }),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'borrador'    => 'gray',
                        'en_revision' => 'warning',
                        'aprobado'    => 'success',
                        'divulgado'   => 'primary',
                        'verificado'  => 'info',
                        'rechazado'   => 'danger',
                        default       => 'gray',
                    }),

                TextColumn::make('fase_dicacocu')
                    ->label('Fase')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('carpeta.nombre')
                    ->label('Carpeta')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('version_actual')
                    ->label('Ver.')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                IconColumn::make('confidencial')
                    ->label('Conf.')
                    ->boolean()
                    ->alignCenter(),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record?->estaVencido() ? 'danger' : null)
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'borrador'    => 'Borrador',
                        'en_revision' => 'En revisión',
                        'aprobado'    => 'Aprobado',
                        'divulgado'   => 'Divulgado',
                        'verificado'  => 'Verificado',
                        'rechazado'   => 'Rechazado',
                    ]),

                SelectFilter::make('tipo_documento')
                    ->label('Tipo')
                    ->options([
                        'procedimiento' => 'Procedimiento',
                        'instructivo'   => 'Instructivo',
                        'formato'       => 'Formato',
                        'manual'        => 'Manual',
                        'politica'      => 'Política',
                        'norma'         => 'Norma',
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
