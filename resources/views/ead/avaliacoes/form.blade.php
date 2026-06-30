@extends('layouts.app')
@section('title', $avaliacao ? 'Editar Avaliação EAD' : 'Nova Avaliação EAD')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">214</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $avaliacao ? 'Editar' : 'Nova' }} Avaliação EAD</h1>
        </div>
        <form action="{{ $avaliacao ? route('ead.avaliacoes.update', $avaliacao) : route('ead.avaliacoes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($avaliacao) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso EAD <span class="text-red-500">*</span></label>
                <select name="curso_ead_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($cursos as $c)<option value="{{ $c->id }}" {{ (string)old('curso_ead_id', $avaliacao->curso_ead_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $avaliacao->titulo ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $avaliacao->descricao ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nota Mínima <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="nota_minima" value="{{ old('nota_minima', $avaliacao->nota_minima ?? '7') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tentativas <span class="text-red-500">*</span></label>
                    <input type="number" min="1" name="tentativas" value="{{ old('tentativas', $avaliacao->tentativas ?? '1') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $avaliacao->ativo ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">Ativo</span>
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.avaliacoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
