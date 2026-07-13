@extends('layouts.app')
@section('title', 'Emissão de Disciplinas dos Alunos')

@section('content')
<x-report-builder codigo="305" titulo="Emissão de Disciplinas dos Alunos" breadcrumb="Acadêmico › Matrícula"
    :emitirRoute="route('academico.emissoes.disciplinas-alunos.emitir')" :funcao="305"
    :catalogo="$catalogo" :colunasSel="$colunasSel" :layouts="$layouts" :layoutAtual="$layoutAtual">

    <div x-show="aba === 'filtros'" class="p-5 space-y-4">
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Turma Montada</label>
                <select name="turmas_montadas[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($turmasMontadas as $tm)<option value="{{ $tm->id }}">{{ $tm->sigla ?: ($tm->nome ?? ('TM #' . $tm->id)) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Disciplinas</label>
                <select name="disciplinas[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Situações da matrícula</label>
                <select name="situacoes[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($situacoes as $s)<option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Tipos de disciplinas</label>
                <select name="tipos[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($tiposDisciplina as $t)<option value="{{ $t }}">{{ ucfirst($t) }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-3 border-t pt-4">
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Início da Matrícula — de</label><input type="date" name="matricula_inicio" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Início da Matrícula — até</label><input type="date" name="matricula_fim" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        </div>
    </div>
</x-report-builder>
@endsection
