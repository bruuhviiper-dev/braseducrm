@extends('layouts.app')
@section('title', 'Emissão de Notas e Faltas')

@section('content')
{{-- 60 Emissão de Notas e Faltas — construtor de relatório (padrão EDUQ) --}}
<div x-data="{ aba: 'geral' }" class="w-full">

    <div class="flex items-start gap-2 mb-4">
        <span class="text-base font-semibold text-gray-400 mt-0.5">60</span>
        <div>
            <h1 class="text-xl font-bold text-gray-800">Emissão de Notas e Faltas</h1>
            <p class="text-xs text-gray-400">Acadêmico › Notas e Faltas</p>
        </div>
    </div>

    <form method="GET" action="{{ route('academico.emissoes.notas-faltas.emitir') }}" target="_blank" class="bg-white rounded-xl border">
        <div class="flex overflow-x-auto border-b text-sm">
            @foreach(['geral' => 'Geral', 'pagina' => 'Layout de Página'] as $k => $tab)
            <button type="button" @click="aba = '{{ $k }}'" :class="aba === '{{ $k }}' ? 'border-cyan-500 text-cyan-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 border-b-2 whitespace-nowrap">{{ $tab }}</button>
            @endforeach
        </div>

        {{-- ABA: Geral --}}
        <div x-show="aba === 'geral'" class="p-5 space-y-4">
            <div>
                <label class="block text-xs text-gray-500 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                {{-- ao trocar a turma, recarrega para popular a lista de Alunos (dependência EDUQ) --}}
                <select name="turma_montada_id" required
                        onchange="window.location='{{ route('academico.emissoes.notas-faltas') }}?turma_montada_id='+this.value"
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" @selected($turmaMontadaId == $tm->id)>{{ $tm->sigla ?: ($tm->nome ?? ('TM #' . $tm->id)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Disciplinas</label>
                    <select name="disciplinas[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Situação</label>
                    <select name="situacoes[]" multiple size="4" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach($situacoes as $s)<option value="{{ $s }}">{{ ucfirst(str_replace('_', ' ', $s)) }}</option>@endforeach
                    </select>
                </div>
            </div>

            {{-- Alunos: depende da Turma Montada. Se não houver, o "+" abre o cadastro sem sair da tela (padrão EDUQ). --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs text-gray-500">Alunos</label>
                    <a href="{{ route('academico.matriculas.wizard') }}" target="_blank" class="text-xs text-cyan-600 hover:underline" title="Cadastrar aluno/matrícula"><i class="fa-solid fa-plus mr-0.5"></i>Novo</a>
                </div>
                @if($turmaMontadaId && $alunos->isNotEmpty())
                <select name="alunos[]" multiple size="5" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($alunos as $m)<option value="{{ $m->id }}">{{ $m->aluno?->pessoa?->nome ?? ('Matrícula #' . $m->id) }}</option>@endforeach
                </select>
                @elseif($turmaMontadaId)
                <div class="border border-dashed rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-400 mb-2">Nenhum aluno matriculado nesta turma.</p>
                    <a href="{{ route('academico.matriculas.wizard') }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-cyan-500 text-white rounded-lg text-xs font-semibold hover:bg-cyan-600"><i class="fa-solid fa-plus"></i> Cadastrar aluno</a>
                </div>
                @else
                <p class="text-sm text-gray-400 border border-dashed rounded-lg p-3 text-center">Selecione a Turma Montada para listar os alunos.</p>
                @endif
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer border-t pt-4">
                <input type="checkbox" name="incluir_notas" value="1" checked class="rounded text-cyan-500">Incluir notas das avaliações?
            </label>
        </div>

        {{-- ABA: Layout de Página --}}
        <div x-show="aba === 'pagina'" x-cloak class="p-5 grid md:grid-cols-2 gap-4">
            <div><label class="block text-xs text-gray-500 mb-1">Orientação</label><select name="orientacao" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="landscape">Paisagem</option><option value="portrait">Retrato</option></select></div>
            <div><label class="block text-xs text-gray-500 mb-1">Papel</label><select name="papel" class="w-full border rounded-lg px-3 py-2 text-sm"><option value="a4">A4</option><option value="letter">Carta</option><option value="legal">Ofício</option></select></div>
        </div>

        <div class="flex justify-end gap-2 px-5 py-4 border-t bg-gray-50 rounded-b-xl">
            <button type="submit" name="formato" value="pdf" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i>PDF</button>
            <button type="submit" name="formato" value="csv" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-semibold hover:bg-green-700"><i class="fa-solid fa-file-csv mr-1"></i>CSV</button>
            <button type="submit" name="formato" value="xlsx" class="px-4 py-2 bg-green-700 text-white rounded-lg text-sm font-semibold hover:bg-green-800"><i class="fa-solid fa-file-excel mr-1"></i>XLSX</button>
        </div>
    </form>
</div>
@endsection
