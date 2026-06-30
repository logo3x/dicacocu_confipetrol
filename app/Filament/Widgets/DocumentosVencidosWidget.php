<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Documentos\DocumentoResource;
use App\Models\Documento;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class DocumentosVencidosWidget extends TableWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getHeading(): ?string
    {
        return 'Documentos Vencidos o Próximos a Vencer (30 días)';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Documento::query()
                    ->with(['responsable', 'carpeta'])
                    ->whereIn('estado', ['aprobado', 'divulgado'])
                    ->whereNotNull('fecha_vencimiento')
                    ->where('fecha_vencimiento', '<=', now()->addDays(30))
                    ->orderBy('fecha_vencimiento')
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

                TextColumn::make('fase_dicacocu')
                    ->label('Fase')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->color(fn ($record) => $record->estaVencido() ? 'danger' : 'warning'),

                TextColumn::make('dias_restantes')
                    ->label('Días restantes')
                    ->state(function ($record): string {
                        $diff = now()->diffInDays($record->fecha_vencimiento, false);
                        if ($diff < 0) {
                            return 'Vencido hace '.abs($diff).' días';
                        }

                        return "Vence en {$diff} días";
                    })
                    ->badge()
                    ->color(fn ($record): string => $record->estaVencido() ? 'danger' : 'warning'),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->placeholder('Sin asignar'),
            ])
            ->recordUrl(fn ($record) => DocumentoResource::getUrl('edit', ['record' => $record]))
            ->recordActions([
                EditAction::make(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Sin documentos próximos a vencer')
            ->emptyStateDescription('No hay documentos vigentes con vencimiento en los próximos 30 días.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
