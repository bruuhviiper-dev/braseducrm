@extends('layouts.app')
@section('title', isset($produto) ? 'Editar Produto' : 'Novo Produto')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($produto) ? 'Editar Produto' : 'Novo Produto de Estoque' }}</h1>

        <form method="POST" action="{{ isset($produto) ? route('estoque.produtos.update', $produto) : route('estoque.produtos.store') }}">
            @csrf
            @if(isset($produto)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $produto->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                    @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Codigo</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $produto->codigo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_estoque_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $c)
                        <option value="{{ $c->id }}" {{ old('categoria_estoque_id', $produto->categoria_estoque_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unidade de Medida</label>
                    <select name="unidade_medida_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="">Selecione...</option>
                        @foreach($unidades as $u)
                        <option value="{{ $u->id }}" {{ old('unidade_medida_id', $produto->unidade_medida_id ?? '') == $u->id ? 'selected' : '' }}>{{ $u->nome }} ({{ $u->sigla }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preco de Custo</label>
                    <input type="number" step="0.01" name="preco_custo" value="{{ old('preco_custo', $produto->preco_custo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estoque Minimo</label>
                    <input type="number" name="estoque_minimo" value="{{ old('estoque_minimo', $produto->estoque_minimo ?? 0) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $produto->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                    Ativo
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('estoque.produtos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
