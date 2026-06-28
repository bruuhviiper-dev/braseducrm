@extends('layouts.app')
@section('title', isset($documento) ? 'Editar Documento' : 'Novo Documento')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($documento) ? 'Editar Documento' : 'Novo Documento' }}</h2>
            <a href="{{ route('documentos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($documento) ? route('documentos.update', $documento) : route('documentos.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($documento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $documento->nome ?? '') }}" placeholder="Ex.: RG, CPF, Histórico Escolar" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso (opcional)</label>
                <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos os cursos</option>
                    @foreach($cursos as $c)
                    <option value="{{ $c->id }}" {{ old('curso_id', $documento->curso_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="obrigatorio" value="1" {{ old('obrigatorio', $documento->obrigatorio ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Obrigatório para matrícula</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $documento->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Ativo</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($documento) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('documentos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
