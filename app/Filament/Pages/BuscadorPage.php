<?php

namespace App\Filament\Pages;

use App\Models\Documento;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
        if (strlen($this->busqueda) < 2 && empty($this->filtroTipo)) {
            return collect();
        }

        $query = Documento::query()
            // El buscador llega a cualquiera con acceso al panel: sin este
            // filtro un confidencial asomaría con título y descripción.
            ->visiblePara(Auth::user())
            ->with(['creador:id,name', 'carpeta:id,nombre', 'responsable:id,name'])
            ->whereIn('estado', ['aprobado', 'divulgado', 'verificado']);

        if (strlen($this->busqueda) >= 2) {
            $termino = $this->busqueda;
            $query->where(function ($q) use ($termino) {
                $q->whereFullText(['titulo', 'descripcion', 'codigo'], $termino)
                    ->orWhere('titulo', 'like', "%{$termino}%")
                    ->orWhere('codigo', 'like', "%{$termino}%");
            });
        }

        if ($this->filtroTipo) {
            $query->where('tipo_documento', $this->filtroTipo);
        }

        return $query->orderByDesc('updated_at')->limit(50)->get();
    }

    protected function getViewData(): array
    {
        return [
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
