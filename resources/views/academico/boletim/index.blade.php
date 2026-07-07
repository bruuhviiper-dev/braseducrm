@extends('layouts.app')
@section('title', 'Cálculo do Boletim')

@php
$badges = [
    'aprovado' => ['bg-green-100 text-green-700', 'Aprovado'],
    'reprovado' => ['bg-red-100 text-red-700', 'Reprovado'],
    'reprovado_falta' => ['bg-orange-100 text-orange-700', 'Reprovado por falta'],
    'cursando' => ['bg-blue-100 text-blue-700', 'Cursando'],
    'em_recuperacao' => ['bg-yellow-100 text-yellow-700', 'Em recuperação'],
];
@endphp

@section('content')
<div class="space-y-6">
    {{-- Filtro (fiel ao EDUQ) --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">2</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Cálculo do Boletim</h1>
                <p class="text-xs text-gray-400">Acadêmico › Notas e Faltas</p>
            </div>
        </div>
        <form method="POST" action="{{ route('academico.boletim.consolidar') }}" class="p-5 space-y-4"
              x-data="{ final: false }"
              @submit="if(final){ return confirm('Calcular o resultado final grava a situação de cada aluno. Continuar?') }">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                <select name="turma_montada_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" {{ $request->turma_montada_id == $tm->id ? 'selected' : '' }}>{{ $tm->sigla ?? $tm->nome ?? $tm->turma?->nome ?? 'Turma '.$tm->id }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina <span class="text-red-500">*</span></label>
                <select name="disciplina_id" class="w-full border rounded-lg px-3 py-2 text-sm" required>
                    <option value="">Selecione...</option>
                    @foreach($disciplinas as $d)
                    <option value="{{ $d->id }}" {{ $request->disciplina_id == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                    @endforeach
                </select>
            </div>
            <label class="flex items-center gap-3 text-sm">
                <input type="checkbox" name="calcular_final" value="1" x-model="final" class="rounded text-primary-600 w-5 h-5">
                <span class="text-gray-700">É para calcular o resultado final dos alunos?</span>
            </label>
            <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700"><i class="fa-solid fa-gears mr-1"></i> Processar</button>
        </form>
    </div>

    {{-- Resultado --}}
    @if($resultado)
        @if(empty($resultado['linhas']))
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum aluno encontrado para esta turma montada.</div>
        @else
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-3 border-b">
                <h2 class="text-sm font-semibold text-gray-700">Resultado <span class="font-normal text-gray-400">(média ≥ {{ number_format($resultado['media_aprovacao'],1,',','.') }} e frequência ≥ {{ number_format($resultado['frequencia_minima'],0) }}% — config. da matriz)</span></h2>
                @if(($resultado['modelo'] ?? 'direto') !== 'direto')
                <p class="text-xs text-gray-400 mt-0.5">
                    Recuperação: liberada com M1 entre {{ number_format($resultado['rec_min'],2,',','.') }} e {{ number_format($resultado['rec_max'],2,',','.') }} —
                    {{ $resultado['modelo'] === 'recuperacao_substitui' ? 'Média Final = REC' : 'Média Final = (M1 + REC) / 2' }},
                    aprovação pós-REC ≥ {{ number_format($resultado['media_aprovacao_final'],2,',','.') }}.
                </p>
                @endif
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        @if(($resultado['modelo'] ?? 'direto') !== 'direto')
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">M1</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">REC</th>
                        @endif
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Média Final</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Frequência</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Situação</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($resultado['linhas'] as $linha)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $linha['matricula']->aluno?->pessoa?->nome ?? 'Matrícula '.$linha['matricula']->id }}</td>
                        @if(($resultado['modelo'] ?? 'direto') !== 'direto')
                        <td class="px-4 py-2 text-center text-gray-600">{{ $linha['m1'] !== null ? number_format($linha['m1'], 2, ',', '.') : '—' }}</td>
                        <td class="px-4 py-2 text-center text-gray-600">
                            @if($linha['rec'] !== null)
                                {{ number_format($linha['rec'], 2, ',', '.') }}
                            @elseif($linha['rec_liberada'])
                                <span class="text-xs text-yellow-600 font-medium">liberada</span>
                            @else
                                —
                            @endif
                        </td>
                        @endif
                        <td class="px-4 py-2 text-center font-semibold {{ $linha['media'] !== null && $linha['media'] >= ($linha['usou_rec'] ? $resultado['media_aprovacao_final'] : $resultado['media_aprovacao']) ? 'text-green-600' : ($linha['media'] !== null ? 'text-red-600' : 'text-gray-400') }}">
                            {{ $linha['media'] !== null ? number_format($linha['media'], 2, ',', '.') : '—' }}
                        </td>
                        <td class="px-4 py-2 text-center text-gray-600">
                            {{ $linha['frequencia'] !== null ? number_format($linha['frequencia'], 1, ',', '.').'%' : '—' }}
                            <span class="text-xs text-gray-400">({{ $linha['presencas'] }}/{{ $linha['total_aulas'] }})</span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $badges[$linha['situacao']][0] }}">{{ $badges[$linha['situacao']][1] }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    @else
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Selecione turma e disciplina e clique em Processar para calcular o boletim.</div>
    @endif
</div>
@endsection
