<?php

namespace App\Filament\Pages;

use App\Models\Documento;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class BuscadorPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static ?string $navigationLabel = 'Buscador de Documentos';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión Documental';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Buscador de Documentos';

    protected string $view = 'filament.pages.buscador-page';

    #[Url(as: 'q')]
    public string $busqueda = '';

    #[Url(as: 'fase')]
    public string $filtroFase = '';

    #[Url(as: 'tipo')]
    public string $filtroTipo = '';

    public function updatedBusqueda(): void
    {
        $this->resetPage();
    }

    protected function resetPage(): void {}

    #[Computed]
    public function resultados(): Collection
    {
        if (strlen($this->busqueda) < 2 && empty($this->filtroFase) && empty($this->filtroTipo)) {
            return collect();
        }

        $query = Documento::query()
            ->with(['creador', 'carpeta', 'responsable'])
            ->whereIn('estado', ['aprobado', 'divulgado', 'verificado']);

        if (strlen($this->busqueda) >= 2) {
            $termino = $this->busqueda;
            $query->where(function ($q) use ($termino) {
                $q->whereFullText(['titulo', 'descripcion', 'codigo'], $termino)
                    ->orWhere('titulo', 'like', "%{$termino}%")
                    ->orWhere('codigo', 'like', "%{$termino}%");
            });
        }

        if ($this->filtroFase) {
            $query->where('fase_dicacocu', $this->filtroFase);
        }

        if ($this->filtroTipo) {
            $query->where('tipo_documento', $this->filtroTipo);
        }

        return $query->orderByDesc('updated_at')->limit(50)->get();
    }

    protected function getViewData(): array
    {
        return [
            'fases' => [
                '' => 'Todas las fases',
                'D' => 'D — Disponibilidad',
                'I' => 'I — Integridad',
                'C' => 'C — Calidad',
                'A' => 'A — Acceso',
                'O' => 'O — Operación',
                'U' => 'U — Uso',
            ],
            'tipos' => [
                '' => 'Todos los tipos',
                'procedimiento' => 'Procedimiento',
                'instructivo' => 'Instructivo',
                'formato' => 'Formato',
                'manual' => 'Manual',
                'politica' => 'Política',
                'norma' => 'Norma',
                'reglamento' => 'Reglamento',
            ],
        ];
    }
}
