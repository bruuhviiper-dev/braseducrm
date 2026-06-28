@extends('layouts.app')
@section('title', 'Integrações')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-plug text-xl text-primary-500"></i>
        <h1 class="text-lg font-semibold text-gray-800">Integrações</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($catalogo as $chave => $def)
        @php $cfg = $configuradas[$chave] ?? null; @endphp
        <div class="bg-white rounded-xl border p-5 flex flex-col">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-{{ $chave === 'whatsapp' ? 'brands' : 'solid' }} {{ $def['icone'] }} text-xl text-primary-500"></i></div>
                @if($cfg && $cfg->ativo)
                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full"><i class="fa-solid fa-circle-check mr-1"></i>Ativa</span>
                @elseif($cfg)
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">Configurada</span>
                @else
                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Não configurada</span>
                @endif
            </div>
            <h3 class="text-sm font-semibold text-gray-800">{{ $def['nome'] }}</h3>
            <p class="text-xs text-gray-500 mt-1 flex-1">{{ $def['descricao'] }}</p>
            @if($cfg && $cfg->ultima_sincronizacao)
            <p class="text-[11px] text-gray-400 mt-2">Última sinc.: {{ $cfg->ultima_sincronizacao->format('d/m/Y H:i') }}</p>
            @endif
            <div class="flex gap-2 mt-4">
                <a href="{{ route('integracoes.edit', $chave) }}" class="flex-1 text-center px-3 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Configurar</a>
                @if($chave === 'rd_station')
                <form method="POST" action="{{ route('integracoes.testar', $chave) }}">
                    @csrf
                    <button type="submit" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50" title="Testar conexão"><i class="fa-solid fa-plug-circle-check"></i></button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
