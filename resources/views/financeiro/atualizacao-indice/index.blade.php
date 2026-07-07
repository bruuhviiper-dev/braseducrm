@extends('layouts.app')
@section('title', 'Atualização de Parcelas pelo Índice')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-2 pt-1 pb-3 flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">175</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Atualização de Parcelas pelo Índice</h1>
                <p class="text-xs text-gray-400">Financeiro › Movimentações — reajuste em lote de parcelas em aberto</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm mb-4">{{ session('success') }}</div>
        @endif

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 px-2 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Índice de reajuste (%) <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="percentual" value="{{ request('percentual') }}" placeholder="Ex.: 5.48 (IGP-M)" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select name="categoria_receber_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Todas</option>
                    @foreach($categorias as $c)<option value="{{ $c->id }}" @selected(request('categoria_receber_id') == $c->id)>{{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parcelas com vencimento a partir de</label>
                <input type="date" name="vencimento_de" value="{{ request('vencimento_de') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-5 py-2 bg-gray-100 border rounded-lg text-sm text-gray-600 hover:bg-gray-200"><i class="fa-solid fa-magnifying-glass mr-1"></i> Simular</button>
            </div>
        </form>

        @if($previa)
        <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4 mx-2 mb-4 text-sm text-cyan-800">
            <p class="font-semibold mb-1"><i class="fa-solid fa-calculator mr-1"></i> Prévia do reajuste de {{ number_format($previa['percentual'], 2, ',', '.') }}%</p>
            <p>{{ $previa['quantidade'] }} parcela(s) em aberto serão atualizadas:
                R$ {{ number_format($previa['total_atual'], 2, ',', '.') }} →
                <strong>R$ {{ number_format($previa['total_novo'], 2, ',', '.') }}</strong></p>
        </div>

        <form method="POST" action="{{ route('financeiro.atualizacao-indice.aplicar') }}" class="px-2">
            @csrf
            <input type="hidden" name="percentual" value="{{ request('percentual') }}">
            <input type="hidden" name="categoria_receber_id" value="{{ request('categoria_receber_id') }}">
            <input type="hidden" name="vencimento_de" value="{{ request('vencimento_de') }}">
            <div class="flex justify-end">
                <button type="submit" onclick="return confirm('Aplicar o índice em {{ $previa['quantidade'] }} parcela(s)? Esta ação altera os valores dos títulos em aberto.')" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-arrow-trend-up mr-1"></i>Aplicar Reajuste</button>
            </div>
        </form>
        @else
        <p class="text-sm text-gray-400 px-2 pb-6">Informe o índice e clique em Simular para ver a prévia antes de aplicar.</p>
        @endif
    </div>
</div>
@endsection
