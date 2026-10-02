<?php

namespace App\Filament\Resources\Documentos\Tables;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Filament\Resources\Documentos\Schemas\DocumentoForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DocumentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // El título muestra la carpeta como subtítulo y hay columna de
            // responsable: sin esto cada fila dispara sus propias consultas.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->with(['carpeta:id,nombre', 'responsable:id,name']))
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->copyable()
                    ->grow(false)
                    ->placeholder('—'),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->titulo)
                    ->description(fn ($record) => $record->carpeta?->nombre),

                TextColumn::make('tipo_documento')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'procedimiento' => 'Procedimiento',
                        'instructivo' => 'Instructivo',
                        'formato' => 'Formato',
                        'manual' => 'Manual',
                        'politica' => 'Política',
                        'norma' => 'Norma',
                        'reglamento' => 'Reglamento',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'procedimiento' => 'primary',
                        'instructivo' => 'info',
                        'formato' => 'gray',
                        'manual' => 'warning',
                        'politica' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'borrador' => 'Borrador',
                        'en_revision' => 'En revisión',
                        'aprobado' => 'Aprobado',
                        'divulgado' => 'Divulgado',
                        'verificado' => 'Verificado',
                        'rechazado' => 'Rechazado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'borrador' => 'gray',
                        'en_revision' => 'warning',
                        'aprobado' => 'success',
                        'divulgado' => 'primary',
                        'verificado' => 'info',
                        'rechazado' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('carpeta.nombre')
                    ->label('Carpeta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('version_actual')
                    ->label('Ver.')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->prefix('v')
                    ->toggleable(),

                IconColumn::make('confidencial')
                    ->label('Conf.')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->options(DocumentoForm::estadosDelDocumento()),

                SelectFilter::make('tipo_documento')
                    ->label('Tipo')
                    ->options(DocumentoForm::tiposDeDocumento()),

                TrashedFilter::make(),
            ])
            ->recordUrl(fn ($record) => DocumentoResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
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
