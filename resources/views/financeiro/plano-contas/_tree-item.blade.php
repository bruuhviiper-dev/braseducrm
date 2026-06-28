@php
    $hasChildren = $conta->filhosRecursivos && $conta->filhosRecursivos->count() > 0;
    $isSintetica = $conta->tipo === 'sintetica';
@endphp

<div x-data="{ expanded: true }">
    <div class="flex items-center gap-2 py-2 px-3 rounded-lg hover:bg-gray-50 group"
         style="padding-left: {{ ($nivel * 24) + 12 }}px">
        {{-- Expand/Collapse --}}
        @if($hasChildren)
        <button type="button" @click="expanded = !expanded" class="w-5 h-5 flex items-center justify-center text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-chevron-right text-xs transition-transform" :class="expanded && 'rotate-90'"></i>
        </button>
        @else
        <span class="w-5 h-5 flex items-center justify-center">
            <i class="fa-solid fa-circle text-[4px] text-gray-300"></i>
        </span>
        @endif

        {{-- Icon --}}
        @if($isSintetica)
        <i class="fa-solid fa-folder text-yellow-500 text-sm"></i>
        @else
        <i class="fa-solid fa-file-alt text-gray-400 text-sm"></i>
        @endif

        {{-- Codigo --}}
        <span class="text-sm font-mono font-semibold text-primary-600 min-w-[60px]">{{ $conta->codigo }}</span>

        {{-- Nome --}}
        <span class="text-sm {{ $isSintetica ? 'font-semibold text-gray-800' : 'text-gray-700' }} flex-1">
            {{ $conta->nome }}
        </span>

        {{-- Badges --}}
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $conta->natureza === 'receita' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($conta->natureza) }}
        </span>

        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $isSintetica ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
            {{ ucfirst($conta->tipo) }}
        </span>

        @if(!$conta->ativo)
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-100 text-red-700">
            Inativo
        </span>
        @endif

        {{-- Actions --}}
        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
            <a href="{{ route('financeiro.plano-contas.edit', $conta) }}" class="p-1 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded" title="Editar">
                <i class="fa-solid fa-pen text-xs"></i>
            </a>
            @if(!$hasChildren)
            <form method="POST" action="{{ route('financeiro.plano-contas.destroy', $conta) }}" class="inline"
                  onsubmit="return confirm('Deseja realmente excluir esta conta?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded" title="Excluir">
                    <i class="fa-solid fa-trash text-xs"></i>
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Children --}}
    @if($hasChildren)
    <div x-show="expanded" x-cloak>
        @foreach($conta->filhosRecursivos->sortBy('codigo') as $filho)
            @include('financeiro.plano-contas._tree-item', ['conta' => $filho, 'nivel' => $nivel + 1])
        @endforeach
    </div>
    @endif
</div>
