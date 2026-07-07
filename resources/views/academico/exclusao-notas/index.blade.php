@extends('layouts.app')
@section('title', 'Exclusão de Notas e Faltas')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">137</span>
            <h1 class="text-lg font-bold text-gray-800">Exclusão de Notas e Faltas</h1>
        </div>
        <form method="POST" action="{{ route('academico.exclusao-notas.excluir') }}" class="p-5 space-y-4"
              onsubmit="return confirm('ATENÇÃO: esta exclusão é DEFINITIVA. Apagar os lançamentos selecionados?')">
            @csrf

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="bg-orange-50 border border-orange-200 text-orange-700 px-4 py-3 rounded text-sm">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Função administrativa: apaga em lote os lançamentos de notas e/ou frequência de uma turma montada × disciplina para relançamento do zero. Não há como desfazer.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                    <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required onchange="this.form.method='GET'; this.form.action='{{ route('academico.exclusao-notas.index') }}'; this.form.submit()">
                        <option value="">Selecione...</option>
                        @foreach($turmasMontadas as $tm)
                        <option value="{{ $tm->id }}" @selected($request->turma_montada_id == $tm->id)>{{ $tm->sigla ?? $tm->nome ?? $tm->turma?->nome ?? 'Turma '.$tm->id }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina <span class="text-red-500">*</span></label>
                    <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm" required onchange="this.form.method='GET'; this.form.action='{{ route('academico.exclusao-notas.index') }}'; this.form.submit()">
                        <option value="">Selecione...</option>
                        @foreach($disciplinas as $d)
                        <option value="{{ $d->id }}" @selected($request->disciplina_id == $d->id)>{{ $d->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($previa)
            <div class="border rounded-lg p-4 bg-gray-50 text-sm text-gray-700">
                Lançamentos encontrados: <strong>{{ $previa['notas'] }}</strong> nota(s) e <strong>{{ $previa['faltas'] }}</strong> registro(s) de frequência.
            </div>
            @endif

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="excluir_notas" value="1" class="rounded border-gray-300 text-red-600"> Excluir notas
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="excluir_faltas" value="1" class="rounded border-gray-300 text-red-600"> Excluir faltas (frequência)
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-500 text-white rounded-full text-sm font-bold shadow-lg shadow-red-500/30"><i class="fa-solid fa-trash mr-1"></i>Excluir definitivamente</button>
            </div>
        </form>
    </div>
</div>
@endsection
