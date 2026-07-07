@extends('layouts.app')
@section('title', $campanha ? 'Editar Campanha' : 'Nova Campanha de Indicação')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">225</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $campanha ? 'Editar Campanha' : 'Nova Campanha de Indicação' }}</h1>
        </div>
        <form action="{{ $campanha ? route('geral.campanhas-indicacao.update', $campanha) : route('geral.campanhas-indicacao.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($campanha) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $campanha->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Início <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($campanha?->data_inicio)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', optional($campanha?->data_fim)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $campanha->descricao ?? '') }}</textarea>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $campanha->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativa
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('geral.campanhas-indicacao.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
