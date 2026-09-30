<?php

namespace App\Filament\Resources\Do\ProcedimientosDo\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Activity;

/**
 * Registro de creaciones, ediciones y eliminaciones del procedimiento.
 */
class HistorialRelationManager extends RelationManager
{
    protected static string $relationship = 'historial';

    protected static ?string $title = 'Historial de cambios';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('causer'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Acción')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, 'Creó') => 'success',
                        str_contains($state, 'Eliminó') => 'danger',
                        default => 'info',
                    }),
                TextColumn::make('causer.name')
                    ->label('Usuario')
                    ->placeholder('Sistema'),
                TextColumn::make('id')
                    ->label('Cambios')
                    // Se lee del registro completo: la columna properties es una
                    // colección y Filament la formatearía elemento por elemento.
                    ->state(fn (Activity $registro): string => self::describirCambios($registro->properties))
                    ->wrap()
                    ->html(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    /** Resume qué campos cambiaron y con qué valores. */
    private static function describirCambios(mixed $propiedades): string
    {
        $datos = $propiedades instanceof Collection
            ? $propiedades->all()
            : (array) $propiedades;

        $nuevos = $datos['attributes'] ?? [];
        $viejos = $datos['old'] ?? [];

        if (blank($nuevos)) {
            return '—';
        }

        $lineas = [];

        foreach ($nuevos as $campo => $valor) {
            $anterior = $viejos[$campo] ?? null;
            $etiqueta = e(str_replace('_', ' ', $campo));

            $lineas[] = array_key_exists($campo, $viejos)
                ? "<strong>{$etiqueta}:</strong> ".e(self::texto($anterior)).' → '.e(self::texto($valor))
                : "<strong>{$etiqueta}:</strong> ".e(self::texto($valor));
        }

        return implode('<br>', array_slice($lineas, 0, 12));
    }

    private static function texto(mixed $valor): string
    {
        return match (true) {
            $valor === null, $valor === '' => 'vacío',
            is_bool($valor) => $valor ? 'sí' : 'no',
            is_array($valor) => json_encode($valor, JSON_UNESCAPED_UNICODE),
            default => (string) $valor,
        };
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
