<?php

namespace App\Filament\Resources\Carpetas\Tables;

use App\Enums\IconoCarpeta;
use App\Models\Carpeta;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class CarpetasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Las subcarpetas quedan justo debajo de su carpeta superior.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderBy('parent_id')
                ->orderBy('orden')
                ->orderBy('nombre'))
            ->columns([
                TextColumn::make('nombre')
                    ->label('Carpeta')
                    ->searchable()
                    ->sortable()
                    ->html()
                    ->formatStateUsing(fn (Carpeta $record): HtmlString => self::nombreConJerarquia($record)),
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->fontFamily('mono')
                    ->placeholder('—'),
                TextColumn::make('creador.name')
                    ->label('Creada por')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('documentos_count')
                    ->label('Documentos')
                    ->counts('documentos')
                    ->alignCenter()
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Pública')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Eliminada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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

    /** Sangría por nivel más el ícono y color propios de la carpeta. */
    private static function nombreConJerarquia(Carpeta $carpeta): HtmlString
    {
        $nivel = $carpeta->profundidad();

        $guia = $nivel > 0
            ? '<span style="color: #9aa3b0; margin-right: .375rem;">&#9492;</span>'
            : '';

        $icono = svg(
            IconoCarpeta::desdeValor($carpeta->icono)->value,
            'w-4 h-4 shrink-0',
            ['style' => 'color: '.e($carpeta->color ?: '#0050A0')],
        )->toHtml();

        return new HtmlString(sprintf(
            '<span style="display: inline-flex; align-items: center; gap: .5rem; padding-left: %srem;">%s%s<span>%s</span></span>',
            $nivel * 1.25,
            $guia,
            $icono,
            e($carpeta->nombre),
        ));
    }
}
