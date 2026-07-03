@props(['show' => null, 'edit' => null, 'delete' => null, 'confirm' => 'Deseja realmente excluir este registro?', 'dir' => 'right'])

{{-- Menu kebab de ações (⋮) estilo EDUQ --}}
<div x-data="{ o: false }" class="relative inline-block">
    <button @click="o = !o" type="button" class="w-8 h-8 border rounded-lg text-gray-500 hover:bg-gray-100 flex items-center justify-center">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>
    <div x-show="o" x-cloak @click.away="o = false"
         class="absolute {{ $dir === 'left' ? 'left-0' : 'right-0' }} mt-1 w-44 bg-white border rounded-lg shadow-xl z-20 py-1 text-left">
        @if($show)
        <a href="{{ $show }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-eye mr-2 text-gray-400"></i>Visualizar</a>
        @endif
        @if($edit)
        <a href="{{ $edit }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-pen mr-2 text-gray-400"></i>Editar</a>
        @endif
        {{ $slot }}
        @if($delete)
        <form method="POST" action="{{ $delete }}" onsubmit="return confirm('{{ $confirm }}')">
            @csrf @method('DELETE')
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"><i class="fa-solid fa-trash mr-2"></i>Excluir</button>
        </form>
        @endif
    </div>
</div>
