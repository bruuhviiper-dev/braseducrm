@props(['title', 'createRoute' => null, 'createLabel' => 'Novo', 'codigo' => null])

<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            @if($codigo)
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">{{ $codigo }}</span>
            @endif
            <h1 class="text-lg font-semibold text-gray-800">{{ $title }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <button class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Filtrar
            </button>
        </div>
    </div>

    <div class="p-4">
        <div class="mb-4">
            <div class="relative max-w-md">
                <input type="text" placeholder="Pesquise por termo ou descricao" class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        {{ $slot }}
    </div>
</div>

@if($createRoute)
{{-- FAB "+" estilo EDUQ (canto inferior direito) --}}
<a href="{{ $createRoute }}" class="group fixed bottom-6 right-6 z-40 flex items-center" title="{{ $createLabel }}">
    <span class="mr-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs font-medium px-2.5 py-1 rounded shadow-lg whitespace-nowrap">{{ $createLabel }}</span>
    <span class="w-14 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-full shadow-xl shadow-primary-600/30 flex items-center justify-center transition-transform group-hover:scale-105 active:scale-95">
        <i class="fa-solid fa-plus text-2xl"></i>
    </span>
</a>
@endif
