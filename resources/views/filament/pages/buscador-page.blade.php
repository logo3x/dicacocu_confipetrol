<x-filament-panels::page>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(0, 80, 160, 0.3); }
            70%  { box-shadow: 0 0 0 8px rgba(0, 80, 160, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 80, 160, 0); }
        }
        .sgd-result-card {
            animation: fadeInUp 0.3s ease both;
        }
        .sgd-result-card:nth-child(1)  { animation-delay: 0.02s; }
        .sgd-result-card:nth-child(2)  { animation-delay: 0.05s; }
        .sgd-result-card:nth-child(3)  { animation-delay: 0.08s; }
        .sgd-result-card:nth-child(4)  { animation-delay: 0.11s; }
        .sgd-result-card:nth-child(5)  { animation-delay: 0.14s; }
        .sgd-result-card:nth-child(6)  { animation-delay: 0.17s; }
        .sgd-result-card:nth-child(n+7){ animation-delay: 0.20s; }

        .sgd-search-wrapper:focus-within .sgd-search-icon {
            color: rgb(0, 80, 160);
            transform: scale(1.1);
            transition: all 0.2s ease;
        }
        .sgd-search-icon {
            transition: all 0.2s ease;
        }
        .sgd-result-card a.doc-title {
            transition: color 0.15s ease;
        }
        .sgd-result-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 80, 160, 0.1);
        }
        .sgd-result-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .sgd-empty-state {
            animation: fadeIn 0.4s ease both;
        }
        .sgd-count-badge {
            animation: fadeIn 0.2s ease both;
        }
    </style>

    {{-- Barra de búsqueda --}}
    <div class="space-y-5">

        {{-- Controles --}}
        <div class="flex gap-3 flex-wrap items-end">

            {{-- Búsqueda con icono --}}
            <div class="flex-1 min-w-64 relative sgd-search-wrapper">
                <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center sgd-search-icon">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" class="h-4 w-4 text-gray-400" />
                </div>
                <x-filament::input.wrapper class="pl-9">
                    <x-filament::input
                        type="search"
                        wire:model.live.debounce.400ms="busqueda"
                        placeholder="Buscar por título, código o descripción..."
                        autofocus
                        class="pl-9"
                    />
                </x-filament::input.wrapper>
            </div>

            {{-- Filtro fase --}}
            <div class="min-w-48">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Fase DICACOCU</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="filtroFase">
                        @foreach($fases as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Filtro tipo --}}
            <div class="min-w-48">
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de documento</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="filtroTipo">
                        @foreach($tipos as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Limpiar filtros --}}
            @if($busqueda || $filtroFase || $filtroTipo)
                <div class="self-end">
                    <x-filament::button
                        wire:click="$set('busqueda', ''); $set('filtroFase', ''); $set('filtroTipo', '')"
                        color="gray"
                        size="sm"
                        icon="heroicon-o-x-mark"
                    >
                        Limpiar
                    </x-filament::button>
                </div>
            @endif
        </div>

        {{-- Estado vacío inicial --}}
        @if(strlen($busqueda) < 2 && !$filtroFase && !$filtroTipo)
            <div class="sgd-empty-state text-center py-20">
                <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-magnifying-glass" class="h-8 w-8 text-primary-400" />
                </div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">Buscar en el repositorio documental</p>
                <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">
                    Busca en documentos vigentes — aprobados, divulgados y verificados del ciclo DICACOCU.
                </p>
                <div class="mt-6 flex justify-center gap-6 text-xs text-gray-400">
                    @foreach(['D — Disponibilidad', 'I — Integridad', 'C — Calidad', 'A — Acceso', 'O — Operación', 'U — Uso'] as $fase)
                        <span class="inline-flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-400 inline-block"></span>
                            {{ $fase }}
                        </span>
                    @endforeach
                </div>
            </div>

        {{-- Sin resultados --}}
        @elseif($this->resultados->isEmpty())
            <div class="sgd-empty-state text-center py-16">
                <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <x-filament::icon icon="heroicon-o-face-frown" class="h-8 w-8 text-gray-400" />
                </div>
                <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">No se encontraron documentos</p>
                <p class="text-sm text-gray-400 mt-1">Intente con otros términos o cambie los filtros.</p>
            </div>

        {{-- Resultados --}}
        @else
            <div class="sgd-count-badge flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-gray-100 dark:bg-gray-800 dark:text-gray-400 px-2.5 py-1 rounded-full">
                    <x-filament::icon icon="heroicon-o-document-magnifying-glass" class="h-3.5 w-3.5" />
                    {{ $this->resultados->count() }} resultado(s) encontrado(s)
                </span>
            </div>

            <div class="grid gap-3">
                @foreach($this->resultados as $doc)
                    <div class="sgd-result-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:border-primary-400 dark:hover:border-primary-500 cursor-pointer group"
                         wire:key="doc-{{ $doc->id }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">

                                {{-- Badges de metadatos --}}
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($doc->codigo)
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300 tracking-tight">
                                            {{ e($doc->codigo) }}
                                        </span>
                                    @endif
                                    @if($doc->fase_dicacocu)
                                        <x-filament::badge color="primary" size="sm">{{ $doc->fase_dicacocu }}</x-filament::badge>
                                    @endif
                                    <x-filament::badge
                                        color="{{ match($doc->tipo_documento) { 'procedimiento' => 'primary', 'instructivo' => 'info', 'manual' => 'warning', default => 'gray' } }}"
                                        size="sm"
                                    >{{ ucfirst(str_replace('_', ' ', e($doc->tipo_documento))) }}</x-filament::badge>
                                    <x-filament::badge
                                        color="{{ match($doc->estado) { 'aprobado' => 'success', 'divulgado' => 'primary', 'verificado' => 'info', default => 'gray' } }}"
                                        size="sm"
                                    >{{ ucfirst(str_replace('_', ' ', $doc->estado)) }}</x-filament::badge>

                                    @if($doc->confidencial)
                                        <x-filament::badge color="danger" size="sm" icon="heroicon-m-lock-closed">Confidencial</x-filament::badge>
                                    @endif
                                </div>

                                {{-- Título --}}
                                <a
                                    href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc]) }}"
                                    class="doc-title text-base font-semibold text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400"
                                >{{ e($doc->titulo) }}</a>

                                {{-- Descripción --}}
                                @if($doc->descripcion)
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                        {{ e($doc->descripcion) }}
                                    </p>
                                @endif

                                {{-- Meta inferior --}}
                                <div class="mt-2.5 flex gap-4 text-xs text-gray-400 flex-wrap items-center">
                                    @if($doc->responsable)
                                        <span class="inline-flex items-center gap-1">
                                            <x-filament::icon icon="heroicon-m-user" class="h-3 w-3" />
                                            {{ e($doc->responsable->name) }}
                                        </span>
                                    @endif
                                    @if($doc->carpeta)
                                        <span class="inline-flex items-center gap-1">
                                            <x-filament::icon icon="heroicon-m-folder" class="h-3 w-3" />
                                            {{ e($doc->carpeta->nombre) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1">
                                        <x-filament::icon icon="heroicon-m-clock" class="h-3 w-3" />
                                        v{{ $doc->version_actual }} · {{ $doc->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Acción ver --}}
                            <div class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                <x-filament::link
                                    :href="\App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc])"
                                    icon="heroicon-m-arrow-top-right-on-square"
                                    size="sm"
                                    color="primary"
                                >Ver</x-filament::link>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</x-filament-panels::page>
