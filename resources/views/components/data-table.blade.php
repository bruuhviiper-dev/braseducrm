@props(['title', 'createRoute' => null, 'createLabel' => 'Novo', 'codigo' => null, 'breadcrumb' => null])

<div class="bg-white rounded-xl border">
    {{-- Header fiel ao EDUQ: código + título + breadcrumb à esquerda; Buscar... + refresh à direita --}}
    <div class="px-5 py-3 border-b flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            @if($codigo)
            <span class="text-sm font-semibold text-gray-400">{{ $codigo }}</span>
            @endif
            <div>
                <h1 class="text-lg font-bold text-gray-800">{{ $title }}</h1>
                @if($breadcrumb)
                <p class="text-xs text-primary-500">{{ $breadcrumb }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                       class="w-64 pl-9 pr-4 py-2 border rounded-full text-sm focus:ring-2 focus:ring-primary-400 focus:border-primary-400 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            </form>
            <button onclick="window.location.href=window.location.pathname" class="p-2 text-gray-400 hover:text-gray-700 rounded-lg" title="Recarregar">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
    </div>

    <div class="p-4">
        {{ $slot }}
    </div>
</div>

@if($createRoute)
{{-- FAB "+" estilo EDUQ (canto inferior direito) --}}
<a href="{{ $createRoute }}" class="group fixed bottom-6 right-6 z-40 flex items-center" title="{{ $createLabel }}">
    <span class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-medium px-2.5 py-1 rounded shadow-lg whitespace-nowrap">{{ $createLabel }}</span>
    <span class="w-14 h-14 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full shadow-xl shadow-cyan-500/30 flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
        <i class="fa-solid fa-plus text-2xl"></i>
    </span>
</a>
@endif
