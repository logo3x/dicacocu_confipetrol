<?php

namespace App\Filament\Widgets;

use App\Enums\EstadoCompromiso;
use App\Filament\Resources\Compromisos\CompromisoResource;
use App\Models\Compromiso;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class CompromisosProximosWidget extends TableWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getHeading(): ?string
    {
        return 'Compromisos Pendientes o Vencidos';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Compromiso::query()
                    ->with('responsable')
                    ->whereNull('cumplido_at')
                    ->orderBy('fecha_limite')
            )
            ->columns([
                TextColumn::make('nombre')
                    ->label('Compromiso')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->nombre),

                TextColumn::make('contrato')
                    ->label('Contrato')
                    ->placeholder('Corporativo'),

                TextColumn::make('fecha_limite')
                    ->label('Fecha límite')
                    ->date('d/m/Y')
                    ->color(fn (Compromiso $record) => $record->estado() === EstadoCompromiso::Vencido ? 'danger' : 'warning'),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->state(fn (Compromiso $record) => $record->estado())
                    ->formatStateUsing(fn (EstadoCompromiso $state): string => $state->label())
                    ->color(fn (EstadoCompromiso $state): string => $state->color()),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->placeholder('Sin asignar'),
            ])
            ->recordUrl(fn ($record) => CompromisoResource::getUrl('edit', ['record' => $record]))
            ->recordActions([
                EditAction::make(),
            ])
            ->paginated(false)
            ->emptyStateHeading('Sin compromisos pendientes')
            ->emptyStateDescription('Todos los compromisos registrados están cumplidos.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}
