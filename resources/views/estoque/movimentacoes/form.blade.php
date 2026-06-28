@extends('layouts.app')
@section('title', isset($movimentacao) ? 'Editar Movimentacao' : 'Nova Movimentacao de Estoque')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($movimentacao) ? 'Editar Movimentacao' : 'Nova Movimentacao de Estoque' }}</h2>
            <a href="{{ route('estoque.movimentacoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($movimentacao) ? route('estoque.movimentacoes.update', $movimentacao) : route('estoque.movimentacoes.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($movimentacao)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produto <span class="text-red-500">*</span></label>
                <select name="produto_estoque_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Selecione...</option>
                    @foreach($produtos as $p)
                    <option value="{{ $p->id }}" {{ old('produto_estoque_id', $movimentacao->produto_estoque_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deposito <span class="text-red-500">*</span></label>
                <select name="deposito_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Selecione...</option>
                    @foreach($depositos as $d)
                    <option value="{{ $d->id }}" {{ old('deposito_id', $movimentacao->deposito_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Selecione...</option>
                    <option value="entrada" {{ old('tipo', $movimentacao->tipo ?? '') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="saida" {{ old('tipo', $movimentacao->tipo ?? '') == 'saida' ? 'selected' : '' }}>Saida</option>
                    <option value="transferencia" {{ old('tipo', $movimentacao->tipo ?? '') == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="quantidade" value="{{ old('quantidade', $movimentacao->quantidade ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Unitario</label>
                    <input type="number" step="0.01" min="0" name="valor_unitario" value="{{ old('valor_unitario', $movimentacao->valor_unitario ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo</label>
                <textarea name="motivo" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('motivo', $movimentacao->motivo ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($movimentacao) ? 'Salvar Alteracoes' : 'Registrar' }}
                </button>
                <a href="{{ route('estoque.movimentacoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
