<?php

namespace App\Filament\Resources\Compromisos\Tables;

use App\Enums\EstadoCompromiso;
use App\Models\Compromiso;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CompromisosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Compromiso')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->nombre),

                TextColumn::make('contrato')
                    ->label('Contrato')
                    ->placeholder('Corporativo')
                    ->searchable(),

                TextColumn::make('fecha_limite')
                    ->label('Fecha límite')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->state(fn (Compromiso $record) => $record->estado())
                    ->formatStateUsing(fn (EstadoCompromiso $state): string => $state->label())
                    ->color(fn (EstadoCompromiso $state): string => $state->color()),

                TextColumn::make('responsable.name')
                    ->label('Responsable')
                    ->placeholder('Sin asignar')
                    ->toggleable(),

                TextColumn::make('rol_responsable')
                    ->label('Rol esperado')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'calidad_corporativa' => 'Calidad Corporativa',
                        'lider_om' => 'Líder O&M',
                        'responsable_hseq' => 'Responsable HSEQ',
                        'personal_tecnico' => 'Personal técnico',
                        default => '—',
                    })
                    ->toggleable(),
            ])
            ->defaultSort('fecha_limite', 'asc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        EstadoCompromiso::Pendiente->value => EstadoCompromiso::Pendiente->label(),
                        EstadoCompromiso::Cumplido->value => EstadoCompromiso::Cumplido->label(),
                        EstadoCompromiso::Vencido->value => EstadoCompromiso::Vencido->label(),
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'pendiente' => $query->whereNull('cumplido_at')->where('fecha_limite', '>=', now()),
                            'cumplido' => $query->whereNotNull('cumplido_at'),
                            'vencido' => $query->whereNull('cumplido_at')->where('fecha_limite', '<', now()),
                            default => $query,
                        };
                    }),

                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('marcarCumplido')
                    ->label('Marcar cumplido')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Compromiso $record) => $record->estado() !== EstadoCompromiso::Cumplido)
                    ->action(fn (Compromiso $record) => $record->marcarCumplido(auth()->user())),

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
