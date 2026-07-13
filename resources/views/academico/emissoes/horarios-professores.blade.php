@extends('layouts.app')
@section('title', 'Emissão de Horários dos Professores')

@section('content')
<x-report-builder codigo="185" titulo="Emissão de Horários dos Professores" breadcrumb="Acadêmico › Turmas"
    :emitirRoute="route('academico.emissoes.horarios-professores.emitir')" :funcao="185"
    :catalogo="$catalogo" :colunasSel="$colunasSel" :layouts="$layouts" :layoutAtual="$layoutAtual">

    <div x-show="aba === 'filtros'" class="p-5 space-y-4">
        <div class="grid md:grid-cols-2 gap-3">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Professores</label>
                <select name="professores[]" multiple size="5" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($professores as $p)<option value="{{ $p->id }}">{{ $p->pessoa?->nome ?? ('Prof. ' . $p->id) }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">Turmas</label>
                <select name="turmas[]" multiple size="5" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($turmas as $t)<option value="{{ $t->id }}">{{ $t->nome }}</option>@endforeach
                </select>
            </div>
        </div>
        <div class="grid md:grid-cols-3 gap-3 border-t pt-4 items-end">
            <div><label class="block text-xs text-gray-500 mb-1">Data da aula</label><span class="text-xs text-gray-400">Intervalo</span></div>
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Início</label><input type="date" name="aula_inicio" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
            <div><label class="block text-[11px] text-gray-400 mb-0.5">Fim</label><input type="date" name="aula_fim" class="w-full border rounded-lg px-3 py-2 text-sm"></div>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Período Letivo</label>
            <select name="periodos[]" multiple size="3" class="w-full md:w-1/2 border rounded-lg px-3 py-2 text-sm">
                @foreach($periodos as $pl)<option value="{{ $pl->id }}">{{ $pl->nome }}</option>@endforeach
            </select>
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer border-t pt-4"><input type="checkbox" name="incluir_inativas" value="1" class="rounded text-cyan-500">Emitir também os dados de turmas inativas?</label>
    </div>
</x-report-builder>
@endsection
