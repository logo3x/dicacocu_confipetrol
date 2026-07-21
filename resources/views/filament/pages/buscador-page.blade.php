<x-filament-panels::page>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* ── Cabecera de búsqueda ── */
        .sgd-search-hero {
            background: linear-gradient(135deg, #0a1f44 0%, #0d2a6e 60%, #0050a0 100%);
            border-radius: 1rem;
            padding: 2rem 2.5rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .sgd-search-hero::before {
            content: '';
            position: absolute;
            top: -30%; right: -5%;
            width: 40%;
            aspect-ratio: 1;
            background: radial-gradient(circle, rgba(232,135,26,0.18) 0%, transparent 65%);
            pointer-events: none;
        }
        .sgd-search-hero-title {
            color: #fff;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .sgd-search-hero-sub {
            color: rgba(255,255,255,0.55);
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
        }

        /* ── Caja de búsqueda principal ── */
        .sgd-search-box {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .sgd-search-input-wrap {
            flex: 1;
            min-width: 260px;
            position: relative;
        }
        .sgd-search-input-wrap input {
            width: 100%;
            padding: 0.65rem 1rem 0.65rem 2.75rem;
            border-radius: 0.625rem;
            border: 1.5px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 0.9375rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            backdrop-filter: blur(4px);
        }
        .sgd-search-input-wrap input::placeholder { color: rgba(255,255,255,0.4); }
        .sgd-search-input-wrap input:focus {
            border-color: #E8871A;
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 0 3px rgba(232,135,26,0.2);
        }
        .sgd-search-input-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.125rem;
            height: 1.125rem;
            color: rgba(255,255,255,0.45);
            pointer-events: none;
            transition: color 0.2s;
        }
        .sgd-search-input-wrap:focus-within .sgd-search-input-icon {
            color: #E8871A;
        }

        /* ── Selects de filtro ── */
        .sgd-filter-group {
            display: flex;
            gap: 0.625rem;
            flex-wrap: wrap;
        }
        .sgd-filter-select {
            padding: 0.625rem 2rem 0.625rem 0.875rem;
            border-radius: 0.625rem;
            border: 1.5px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.1);
            color: #fff;
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            backdrop-filter: blur(4px);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='rgba(255,255,255,0.5)' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 1rem;
            transition: border-color 0.2s, background-color 0.2s;
            outline: none;
            min-width: 160px;
        }
        .sgd-filter-select option { background: #0d2a6e; color: #fff; }
        .sgd-filter-select:focus { border-color: #E8871A; }

        /* ── Resultados ── */
        .sgd-result-card {
            animation: fadeInUp 0.28s ease both;
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .sgd-result-card:nth-child(1) { animation-delay: 0.02s; }
        .sgd-result-card:nth-child(2) { animation-delay: 0.05s; }
        .sgd-result-card:nth-child(3) { animation-delay: 0.08s; }
        .sgd-result-card:nth-child(n+4) { animation-delay: 0.11s; }
        .sgd-result-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0,80,160,0.1);
        }

        /* ── Sin resultados ── */
        .sgd-empty { animation: fadeIn 0.35s ease both; }

        /* ── Conteo ── */
        .sgd-count { animation: fadeIn 0.2s ease both; }
    </style>

    <div class="space-y-4">

        {{-- ── Hero de búsqueda ── --}}
        <div class="sgd-search-hero">
            <p class="sgd-search-hero-title">Repositorio documental DICACOCU</p>
            <p class="sgd-search-hero-sub">Busca procedimientos, instructivos, formatos y más — solo documentos aprobados, divulgados y verificados.</p>

            <div class="sgd-search-box">
                {{-- Input principal --}}
                <div class="sgd-search-input-wrap">
                    <svg class="sgd-search-input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input
                        type="search"
                        wire:model.live.debounce.350ms="busqueda"
                        placeholder="Buscar por título, código o descripción…"
                        autofocus
                    >
                </div>

                {{-- Filtros --}}
                <div class="sgd-filter-group">
                    <select class="sgd-filter-select" wire:model.live="filtroTipo">
                        @foreach($tipos as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    @if($busqueda || $filtroTipo)
                        <button
                            wire:click="$set('busqueda', ''); $set('filtroTipo', '')"
                            class="px-3 py-2 rounded-lg border border-white/20 bg-white/10 text-white/70 text-xs font-medium hover:bg-white/20 hover:text-white transition-all"
                            title="Limpiar filtros"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Estado inicial ── --}}
        @if(strlen($busqueda) < 2 && !$filtroTipo)
            <div class="sgd-empty text-center py-12">
                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Escribe al menos 2 caracteres para comenzar</p>
            </div>

        {{-- ── Sin resultados ── --}}
        @elseif($this->resultados->isEmpty())
            <div class="sgd-empty text-center py-12">
                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
                </svg>
                <p class="text-gray-600 dark:text-gray-300 font-medium">No se encontraron documentos</p>
                <p class="text-sm text-gray-400 mt-1">Prueba con otros términos o cambia los filtros.</p>
            </div>

        {{-- ── Resultados ── --}}
        @else
            <div class="sgd-count flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-full">
                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    {{ $this->resultados->count() }} documento(s) encontrado(s)
                </span>
            </div>

            <div class="grid gap-3">
                @foreach($this->resultados as $doc)
                    <div
                        class="sgd-result-card bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:border-primary-400 dark:hover:border-primary-500 group"
                        wire:key="doc-{{ $doc->id }}"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">

                                {{-- Badges --}}
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    @if($doc->codigo)
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-600 dark:text-gray-300 tracking-tight">
                                            {{ e($doc->codigo) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ match($doc->tipo_documento) {
                                            'procedimiento' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                            'instructivo'   => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/40 dark:text-cyan-300',
                                            'manual'        => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300',
                                            'formato'       => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                            default         => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                        } }}">
                                        {{ ucfirst(str_replace('_', ' ', e($doc->tipo_documento))) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ match($doc->estado) {
                                            'aprobado'  => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                            'divulgado' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                                            'verificado'=> 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300',
                                            default     => 'bg-gray-100 text-gray-600',
                                        } }}">
                                        {{ ucfirst(str_replace('_', ' ', $doc->estado)) }}
                                    </span>
                                    @if($doc->confidencial)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd"/></svg>
                                            Confidencial
                                        </span>
                                    @endif
                                </div>

                                {{-- Título --}}
                                <a
                                    href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc]) }}"
                                    class="text-base font-semibold text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
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
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z"/></svg>
                                            {{ e($doc->responsable->name) }}
                                        </span>
                                    @endif
                                    @if($doc->carpeta)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2 6a2 2 0 0 1 2-2h4l2 2h5a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Z"/></svg>
                                            {{ e($doc->carpeta->nombre) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd"/></svg>
                                        v{{ $doc->version_actual }} · {{ $doc->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Botón ver --}}
                            <div class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a
                                    href="{{ \App\Filament\Resources\Documentos\DocumentoResource::getUrl('view', ['record' => $doc]) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 px-3 py-1.5 rounded-lg transition-colors"
                                >
                                    <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5Zm6.75-3a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0V4.06l-6.22 6.22a.75.75 0 0 1-1.06-1.06L14.44 3h-3.44a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</x-filament-panels::page>
