@extends('layouts.app')
@section('title', 'Emissão de Pendências de Notas e Faltas')

@section('content')
<x-report-builder codigo="249" titulo="Emissão de Pendências de Notas e Faltas" breadcrumb="Acadêmico › Notas e Faltas"
    :emitirRoute="route('academico.emissoes.pendencias-notas-faltas.emitir')" :funcao="249"
    :catalogo="$catalogo" :colunasSel="$colunasSel" :layouts="$layouts" :layoutAtual="$layoutAtual">

    <div x-show="aba === 'filtros'" class="p-5 space-y-4">
        <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="emitir_notas" value="1" checked class="rounded text-cyan-500">Emitir Notas Pendentes</label>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer"><input type="checkbox" name="emitir_freq" value="1" checked class="rounded text-cyan-500">Emitir Frequências Pendentes</label>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Turma Montada <span class="text-red-500">*</span></label>
            <select name="turma_montada_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Selecione...</option>
                @foreach($turmasMontadas as $tm)<option value="{{ $tm->id }}">{{ $tm->sigla ?: ($tm->nome ?? ('TM #' . $tm->id)) }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Professor(a)</label>
            <select name="professor_id" class="w-full md:w-1/2 border rounded-lg px-3 py-2 text-sm">
                <option value="">Todos</option>
                @foreach($professores as $p)<option value="{{ $p->id }}">{{ $p->pessoa?->nome ?? ('Prof. ' . $p->id) }}</option>@endforeach
            </select>
        </div>
        <div class="grid md:grid-cols-2 gap-3 border-t pt-4">
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Período — Início</label><input type="date" name="periodo_inicio" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Período — Fim</label><input type="date" name="periodo_fim" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        </div>
    </div>
</x-report-builder>
@endsection
