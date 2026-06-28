@extends('layouts.app')
@section('title', isset($classificacao) ? 'Editar Classificação' : 'Nova Classificação')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($classificacao) ? 'Editar Classificação GED' : 'Nova Classificação GED' }}</h2>
            <a href="{{ route('ged.classificacoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($classificacao) ? route('ged.classificacoes.update', $classificacao) : route('ged.classificacoes.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($classificacao)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $classificacao->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classificação Pai</label>
                <select name="pai_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Nível raiz —</option>
                    @foreach($pais as $p)
                    <option value="{{ $p->id }}" {{ old('pai_id', $classificacao->pai_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $classificacao->descricao ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $classificacao->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ isset($classificacao) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('ged.classificacoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
