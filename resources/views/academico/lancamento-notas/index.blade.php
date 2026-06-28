@extends('layouts.app')
@section('title', 'Lançamento de Avaliação')

@section('content')
<div class="space-y-6">
    {{-- Filtro / seleção --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">1</span>
            <h1 class="text-lg font-semibold text-gray-800">Lançamento de Avaliação</h1>
        </div>
        <form method="GET" action="{{ route('academico.lancamento-notas.index') }}" class="p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Turma Montada</label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" {{ $request->turma_montada_id == $tm->id ? 'selected' : '' }}>{{ $tm->nome ?? $tm->turma?->nome ?? 'Turma '.$tm->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Disciplina</label>
                <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($disciplinas as $d)
                    <option value="{{ $d->id }}" {{ $request->disciplina_id == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tabela de Avaliação</label>
                <select name="tabela_avaliacao_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($tabelas as $t)
                    <option value="{{ $t->id }}" {{ $request->tabela_avaliacao_id == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-magnifying-glass mr-1"></i> Carregar</button>
            </div>
        </form>
    </div>

    {{-- Grade de notas --}}
    @if($grade)
        @if($grade['matriculas']->isEmpty())
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum aluno matriculado nesta turma montada.</div>
        @elseif($grade['tabela']->itens->isEmpty())
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">A tabela de avaliação selecionada não possui itens. Cadastre os itens primeiro.</div>
        @else
        <form method="POST" action="{{ route('academico.lancamento-notas.salvar') }}" class="bg-white rounded-xl border overflow-hidden">
            @csrf
            <input type="hidden" name="turma_montada_id" value="{{ $request->turma_montada_id }}">
            <input type="hidden" name="disciplina_id" value="{{ $request->disciplina_id }}">
            <input type="hidden" name="tabela_avaliacao_id" value="{{ $request->tabela_avaliacao_id }}">

            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Notas — {{ $grade['tabela']->nome }} (máx. {{ number_format($grade['tabela']->nota_maxima, 1, ',', '.') }})</h2>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700"><i class="fa-solid fa-floppy-disk mr-1"></i> Salvar Notas</button>
            </div>

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        @foreach($grade['tabela']->itens as $item)
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">{{ $item->nome }}<br><span class="text-[10px] text-gray-400">peso {{ rtrim(rtrim(number_format($item->peso,2,',','.'),'0'),',') }}</span></th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($grade['matriculas'] as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? 'Matrícula '.$m->id }}</td>
                        @foreach($grade['tabela']->itens as $item)
                        @php $valor = $grade['notas']->get($m->id.'-'.$item->id)?->first()?->nota; @endphp
                        <td class="px-2 py-2 text-center">
                            <input type="number" step="0.01" min="0" max="{{ $grade['tabela']->nota_maxima }}"
                                   name="notas[{{ $m->id }}][{{ $item->id }}]" value="{{ $valor }}"
                                   class="w-20 border rounded px-2 py-1.5 text-sm text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @endif
    @else
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Selecione turma, disciplina e tabela de avaliação para lançar as notas.</div>
    @endif
</div>
@endsection
