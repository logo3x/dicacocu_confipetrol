<x-filament-panels::page>

    {{-- Barra de búsqueda --}}
    <div class="space-y-4">
        <div class="flex gap-3 flex-wrap">
            <div class="flex-1 min-w-64">
                <x-filament::input.wrapper>
                    <x-filament::input
                        type="search"
                        wire:model.live.debounce.400ms="busqueda"
                        placeholder="Buscar por título, código o descripción..."
                        autofocus
                    />
                </x-filament::input.wrapper>
            </div>
            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="filtroFase">
                        @foreach($fases as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <div>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="filtroTipo">
                        @foreach($tipos as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        {{-- Resultados --}}
        @if(strlen($busqueda) < 2 && !$filtroFase && !$filtroTipo)
            <div class="text-center py-16 text-gray-400">
                <x-filament::icon icon="heroicon-o-magnifying-glass" class="mx-auto mb-3 h-12 w-12 opacity-40" />
                <p class="text-lg font-medium">Ingrese al menos 2 caracteres para buscar</p>
                <p class="text-sm mt-1">Busca en documentos vigentes (aprobados, divulgados y verificados)</p>
            </div>
        @elseif($this->resultados->isEmpty())
            <div class="text-center py-16 text-gray-400">
                <x-filament::icon icon="heroicon-o-face-frown" class="mx-auto mb-3 h-12 w-12 opacity-40" />
                <p class="text-lg font-medium">No se encontraron documentos</p>
                <p class="text-sm mt-1">Intente con otros términos o cambie los filtros</p>
            </div>
        @else
            <p class="text-sm text-gray-500">{{ $this->resultados->count() }} resultado(s) encontrado(s)</p>

            <div class="grid gap-3">
                @foreach($this->resultados as $doc)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:border-primary-400 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    @if($doc->codigo)
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300">{{ $doc->codigo }}</span>
                                    @endif
                                    @if($doc->fase_dicacocu)
                                        <x-filament::badge color="primary" size="sm">{{ $doc->fase_dicacocu }}</x-filament::badge>
                                    @endif
                                    <x-filament::badge
                                        color="{{ match($doc->tipo_documento) { 'procedimiento' => 'primary', 'instructivo' => 'info', 'manual' => 'warning', default => 'gray' } }}"
                                        size="sm"
                                    >{{ ucfirst(str_replace('_', ' ', $doc->tipo_documento)) }}</x-filament::badge>
                                    <x-filament::badge
                                        color="{{ match($doc->estado) { 'aprobado' => 'success', 'divulgado' => 'primary', 'verificado' => 'info', default => 'gray' } }}"
                                        size="sm"
                                    >{{ ucfirst(str_replace('_', ' ', $doc->estado)) }}</x-filament::badge>
                                </div>
                                <a
                                    href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc]) }}"
                                    class="text-base font-semibold text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400"
                                >{{ $doc->titulo }}</a>
                                @if($doc->descripcion)
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">{{ $doc->descripcion }}</p>
                                @endif
                                <div class="mt-2 flex gap-4 text-xs text-gray-400 flex-wrap">
                                    @if($doc->responsable)
                                        <span>Responsable: {{ $doc->responsable->name }}</span>
                                    @endif
                                    @if($doc->carpeta)
                                        <span>Carpeta: {{ $doc->carpeta->nombre }}</span>
                                    @endif
                                    <span>v{{ $doc->version_actual }} · Actualizado {{ $doc->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <x-filament::link
                                    :href="\App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc])"
                                    icon="heroicon-m-arrow-top-right-on-square"
                                    size="sm"
                                >Ver</x-filament::link>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-filament-panels::page>
