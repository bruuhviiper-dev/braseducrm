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
            @if($createRoute)
            <a href="{{ $createRoute }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> {{ $createLabel }}
            </a>
            @endif
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
