<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Models\Documento;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DocumentosRecientesWidget extends TableWidget
{
    protected static ?string $heading = 'Documentos recientes';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Documento::query()
                    ->with(['creador', 'responsable', 'carpeta'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->fontFamily('mono')
                    ->placeholder('—'),

                TextColumn::make('titulo')
                    ->label('Título')
                    ->limit(45)
                    ->tooltip(fn ($record) => $record->titulo),

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

                TextColumn::make('creador.name')
                    ->label('Creado por'),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordUrl(fn ($record) => DocumentoResource::getUrl('view', ['record' => $record]))
            ->recordActions([
                ViewAction::make(),
            ])
            ->paginated(false);
    }
}
