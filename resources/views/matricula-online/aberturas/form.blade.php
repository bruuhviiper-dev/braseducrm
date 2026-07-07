@extends('layouts.app')
@section('title', isset($abertura) ? 'Editar Abertura' : 'Nova Abertura')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($abertura) ? 'Editar Abertura' : 'Nova Abertura de Matrícula Online' }}</h2>
            <a href="{{ route('matricula-online.aberturas.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($abertura) ? route('matricula-online.aberturas.update', $abertura) : route('matricula-online.aberturas.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($abertura)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $abertura->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    @foreach($cursos as $c)
                    <option value="{{ $c->id }}" {{ old('curso_id', $abertura->curso_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Início <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', isset($abertura) ? $abertura->data_inicio->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', isset($abertura) ? $abertura->data_fim->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Matrícula</label>
                    <input type="number" step="0.01" name="valor_matricula" value="{{ old('valor_matricula', $abertura->valor_matricula ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Curso</label>
                    <input type="number" step="0.01" name="valor_curso" value="{{ old('valor_curso', $abertura->valor_curso ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vagas</label>
                    <input type="number" min="0" name="vagas" value="{{ old('vagas', $abertura->vagas ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $abertura->descricao ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $abertura->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativa</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">
                    {{ isset($abertura) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('matricula-online.aberturas.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
