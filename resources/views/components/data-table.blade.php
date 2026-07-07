@props(['title', 'createRoute' => null, 'createLabel' => 'Novo', 'codigo' => null, 'breadcrumb' => null])

<div class="bg-white" x-data="{ showFiltros: false }" @toggle-filtros.window="showFiltros = !showFiltros">
    {{-- Header fiel ao EDUQ Clean UI: código + título grande + breadcrumb; Buscar pill + refresh à direita --}}
    <div class="px-2 pt-1 pb-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-start gap-2">
            @if($codigo)
            <span class="text-base font-semibold text-gray-400 mt-0.5">{{ $codigo }}</span>
            @endif
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $title }}</h1>
                @if($breadcrumb)
                <p class="text-xs text-gray-400">{{ $breadcrumb }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                       class="w-36 sm:w-72 pl-9 pr-4 py-2 border-2 border-gray-200 rounded-full text-sm focus:border-cyan-400 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            </form>
            <button onclick="window.location.href=window.location.pathname" class="p-2 text-gray-400 hover:text-gray-700 rounded-lg" title="Recarregar">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>

    @isset($filtros)
    {{-- Painel de Filtros (aberto pelo funil flutuante, estilo EDUQ) --}}
    <div x-show="showFiltros" x-cloak class="px-4 py-4 border-b bg-gray-50 rounded-lg mb-2">
        {{ $filtros }}
    </div>
    @endisset

    <div>
        {{ $slot }}
    </div>
</div>

{{-- Pilha de botões flutuantes (estilo EDUQ: expandir + funil + FAB "+") --}}
<div class="fixed bottom-6 right-6 z-40 flex flex-col items-end gap-2.5">
    <button onclick="document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen()"
            class="w-10 h-10 bg-white border shadow-md rounded-lg text-gray-500 hover:text-gray-800 flex items-center justify-center" title="Expandir">
        <i class="fa-solid fa-up-right-and-down-left-from-center text-sm"></i>
    </button>
    @isset($filtros)
    <button onclick="window.dispatchEvent(new CustomEvent('toggle-filtros'))"
            class="w-10 h-10 bg-slate-600 shadow-md rounded-lg text-white hover:bg-slate-500 flex items-center justify-center" title="Filtros">
        <i class="fa-solid fa-filter text-sm"></i>
    </button>
    @endisset
    @if($createRoute)
    <a href="{{ $createRoute }}" class="group flex items-center" title="{{ $createLabel }}">
        <span class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-medium px-2.5 py-1 rounded shadow-lg whitespace-nowrap">{{ $createLabel }}</span>
        <span class="w-12 h-12 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full shadow-xl shadow-cyan-500/30 flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
            <i class="fa-solid fa-plus text-xl"></i>
        </span>
    </a>
    @endif
</div>
