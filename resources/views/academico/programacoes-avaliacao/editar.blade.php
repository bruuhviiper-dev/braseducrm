@extends('layouts.app')
@section('title', 'Programação de Avaliação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">4</span>
                <div>
                    <h1 class="text-base font-semibold text-gray-800">{{ $turma_montada->nome ?? 'Turma #'.$turma_montada->id }}</h1>
                    <p class="text-xs text-gray-500">{{ $disciplina->nome }}</p>
                </div>
            </div>
            <a href="{{ route('academico.programacoes-avaliacao.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form action="{{ route('academico.programacoes-avaliacao.salvar', [$turma_montada->id, $disciplina->id]) }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tabela de Avaliação <span class="text-red-500">*</span></label>
                <select name="tabela_avaliacao_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Selecione...</option>
                    @foreach($tabelas as $t)
                    <option value="{{ $t->id }}" {{ (string) old('tabela_avaliacao_id', $programacao->tabela_avaliacao_id ?? '') === (string) $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data da Avaliação</label>
                <input type="date" name="data_avaliacao" value="{{ old('data_avaliacao', optional($programacao->data_avaliacao)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.programacoes-avaliacao.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar Programação</button>
            </div>
        </form>
    </div>
</div>
@endsection
