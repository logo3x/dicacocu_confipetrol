<?php

namespace App\Filament\Widgets;

use App\Models\Documento;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class ComplianceFasesWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getHeading(): ?string
    {
        return 'Reporte de Compliance por Fase DICACOCU';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Documento::query()
                    ->whereNotNull('fase_dicacocu')
                    ->selectRaw('
                        fase_dicacocu,
                        COUNT(*) as total,
                        SUM(CASE WHEN estado IN ("aprobado","divulgado","verificado") THEN 1 ELSE 0 END) as vigentes,
                        SUM(CASE WHEN estado = "en_revision" THEN 1 ELSE 0 END) as en_revision,
                        SUM(CASE WHEN estado IN ("borrador","rechazado") THEN 1 ELSE 0 END) as pendientes,
                        SUM(CASE WHEN fecha_vencimiento < NOW() AND estado IN ("aprobado","divulgado") THEN 1 ELSE 0 END) as vencidos
                    ')
                    ->groupBy('fase_dicacocu')
                    ->orderBy('fase_dicacocu')
            )
            ->columns([
                TextColumn::make('fase_dicacocu')
                    ->label('Fase')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'D' => 'D — Disponibilidad',
                        'I' => 'I — Integridad',
                        'C' => 'C — Calidad',
                        'A' => 'A — Acceso',
                        'O' => 'O — Operación',
                        'U' => 'U — Uso',
                        default => $state,
                    }),

                TextColumn::make('total')
                    ->label('Total docs')
                    ->numeric()
                    ->alignCenter(),

                TextColumn::make('vigentes')
                    ->label('Vigentes')
                    ->numeric()
                    ->alignCenter()
                    ->color('success'),

                TextColumn::make('en_revision')
                    ->label('En revisión')
                    ->numeric()
                    ->alignCenter()
                    ->color('warning'),

                TextColumn::make('pendientes')
                    ->label('Pendientes')
                    ->numeric()
                    ->alignCenter()
                    ->color('gray'),

                TextColumn::make('vencidos')
                    ->label('Vencidos')
                    ->numeric()
                    ->alignCenter()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),

                TextColumn::make('compliance')
                    ->label('% Compliance')
                    ->state(function ($record): string {
                        if ($record->total === 0) {
                            return '—';
                        }
                        $pct = round(($record->vigentes / $record->total) * 100, 1);

                        return "{$pct}%";
                    })
                    ->badge()
                    ->color(function ($record): string {
                        if ($record->total === 0) {
                            return 'gray';
                        }
                        $pct = ($record->vigentes / $record->total) * 100;

                        return match (true) {
                            $pct >= 80 => 'success',
                            $pct >= 50 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->alignCenter(),
            ])
            ->paginated(false);
    }
}
