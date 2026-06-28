@extends('layouts.app')
@section('title', isset($categoria) ? 'Editar Categoria' : 'Nova Categoria')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($categoria) ? 'Editar Categoria' : 'Nova Categoria a Pagar' }}</h2>
            <a href="{{ route('financeiro.categorias-pagar.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($categoria) ? route('financeiro.categorias-pagar.update', $categoria) : route('financeiro.categorias-pagar.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($categoria)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $categoria->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plano de Contas</label>
                <select name="plano_conta_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    @foreach($planos as $p)
                    <option value="{{ $p->id }}" {{ old('plano_conta_id', $categoria->plano_conta_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->codigo }} - {{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($categoria) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('financeiro.categorias-pagar.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
