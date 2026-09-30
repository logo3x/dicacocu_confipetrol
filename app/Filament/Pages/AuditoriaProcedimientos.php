<?php

namespace App\Filament\Pages;

use App\Models\Do\ProcedimientoDo;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

/**
 * Auditoría de los cambios registrados sobre los procedimientos.
 */
class AuditoriaProcedimientos extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.auditoria-procedimientos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Auditoría';

    protected static string|\UnitEnum|null $navigationGroup = 'Procedimientos DICACOCU';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'auditoria-procedimientos';

    protected static ?string $title = 'Auditoría de procedimientos';

    public static function canAccess(): bool
    {
        return Auth::user()?->veTodosLosContratos() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Activity::query()
                ->where('subject_type', ProcedimientoDo::class)
                ->with(['causer', 'subject']))
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
                TextColumn::make('subject.nombre_actividad')
                    ->label('Procedimiento')
                    ->placeholder('Eliminado')
                    ->wrap()
                    ->limit(50),
                TextColumn::make('causer.name')
                    ->label('Usuario')
                    ->placeholder('Sistema')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('causer_id')
                    ->label('Usuario')
                    ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable(),
                Filter::make('desde')
                    ->schema([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['desde'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('created_at', '>=', $fecha))
                        ->when($data['hasta'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('created_at', '<=', $fecha))),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
