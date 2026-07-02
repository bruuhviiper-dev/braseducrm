@extends('layouts.app')
@section('title', ($movimentacao ?? null) ? 'Editar Movimentação' : 'Movimentações de Estoque')

@section('content')
<div class="max-w-3xl mx-auto"
     x-data="{
        tipo: '{{ old('tipo', $movimentacao->tipo ?? 'entrada') }}',
        qtd: {{ old('quantidade', $movimentacao->quantidade ?? 0) ?: 0 }},
        valor: {{ old('valor_unitario', $movimentacao->valor_unitario ?? 0) ?: 0 }},
        get total() { return (Number(this.qtd||0) * Number(this.valor||0)); }
     }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('estoque.movimentacoes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">150</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Movimentações de Estoque</h1>
                <p class="text-xs text-gray-400">Estoque › Movimentações</p>
            </div>
        </div>

        <div class="border-b px-4">
            <span class="inline-block px-4 py-2.5 text-sm font-medium border-b-2 border-primary-600 text-primary-600">Dados Básicos</span>
        </div>

        <form method="POST" action="{{ ($movimentacao ?? null) ? route('estoque.movimentacoes.update', $movimentacao) : route('estoque.movimentacoes.store') }}" class="p-6 space-y-4">
            @csrf
            @if($movimentacao ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" x-model="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                        <option value="transferencia">Transferência</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" x-text="tipo==='transferencia' ? 'Data da Transferência *' : 'Data da Movimentação'"></label>
                    <input type="date" name="data_movimentacao" value="{{ old('data_movimentacao', optional($movimentacao->data_movimentacao ?? null)->format('Y-m-d') ?: date('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Produto <span class="text-red-500">*</span></label>
                    <select name="produto_estoque_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($produtos as $p)<option value="{{ $p->id }}" @selected(old('produto_estoque_id', $movimentacao->produto_estoque_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            {{-- Depósito (entrada/saída) --}}
            <div x-show="tipo!=='transferencia'">
                <label class="block text-sm font-medium text-gray-700 mb-1">Depósito <span class="text-red-500">*</span></label>
                <select name="deposito_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($depositos as $d)<option value="{{ $d->id }}" @selected(old('deposito_id', $movimentacao->deposito_id ?? '')==$d->id)>{{ $d->nome }}</option>@endforeach
                </select>
            </div>

            {{-- Depósito Origem/Destino (transferência) --}}
            <div x-show="tipo==='transferencia'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Depósito (Origem) <span class="text-red-500">*</span></label>
                    <select name="deposito_origem_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($depositos as $d)<option value="{{ $d->id }}" @selected(old('deposito_origem_id', $movimentacao->deposito_origem_id ?? '')==$d->id)>{{ $d->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Depósito (Destino) <span class="text-red-500">*</span></label>
                    <select name="deposito_destino_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($depositos as $d)<option value="{{ $d->id }}" @selected(old('deposito_destino_id', $movimentacao->deposito_destino_id ?? '')==$d->id)>{{ $d->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantidade" x-model.number="qtd" value="{{ old('quantidade', $movimentacao->quantidade ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Unitário (R$)</label>
                    <input type="number" step="0.01" min="0" name="valor_unitario" x-model.number="valor" value="{{ old('valor_unitario', $movimentacao->valor_unitario ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="motivo" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('motivo', $movimentacao->motivo ?? '') }}</textarea>
            </div>

            {{-- Total (fiel ao EDUQ) --}}
            <div class="text-center py-2 border-t">
                <span class="text-primary-600 font-medium">Total da movimentação </span>
                <span class="text-primary-600 font-bold text-lg" x-text="'R$ ' + total.toLocaleString('pt-BR',{minimumFractionDigits:2, maximumFractionDigits:2})"></span>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('estoque.movimentacoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
