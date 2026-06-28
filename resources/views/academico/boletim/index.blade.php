@extends('layouts.app')
@section('title', 'Cálculo do Boletim')

@php
$badges = [
    'aprovado' => ['bg-green-100 text-green-700', 'Aprovado'],
    'reprovado' => ['bg-red-100 text-red-700', 'Reprovado'],
    'reprovado_falta' => ['bg-orange-100 text-orange-700', 'Reprovado por falta'],
    'cursando' => ['bg-blue-100 text-blue-700', 'Cursando'],
];
@endphp

@section('content')
<div class="space-y-6">
    {{-- Filtro --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">2</span>
            <h1 class="text-lg font-semibold text-gray-800">Cálculo do Boletim</h1>
        </div>
        <form method="GET" action="{{ route('academico.boletim.index') }}" class="p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
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
                <label class="block text-xs font-medium text-gray-600 mb-1">Config. Boletim</label>
                <select name="configuracao_boletim_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Padrão (média 7 / freq 75%)</option>
                    @foreach($configuracoes as $c)
                    <option value="{{ $c->id }}" {{ $request->configuracao_boletim_id == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-calculator mr-1"></i> Calcular</button>
            </div>
        </form>
    </div>

    {{-- Resultado --}}
    @if($resultado)
        @if(empty($resultado['linhas']))
        <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Nenhum aluno encontrado para esta turma montada.</div>
        @else
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-3 border-b flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700">Resultado (média ≥ {{ number_format($resultado['media_aprovacao'],1,',','.') }} e frequência ≥ {{ number_format($resultado['frequencia_minima'],0) }}%)</h2>
                <form method="POST" action="{{ route('academico.boletim.consolidar') }}" onsubmit="return confirm('Consolidar e gravar a situação de cada aluno?')">
                    @csrf
                    <input type="hidden" name="turma_montada_id" value="{{ $request->turma_montada_id }}">
                    <input type="hidden" name="disciplina_id" value="{{ $request->disciplina_id }}">
                    <input type="hidden" name="configuracao_boletim_id" value="{{ $request->configuracao_boletim_id }}">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"><i class="fa-solid fa-check-double mr-1"></i> Consolidar Boletim</button>
                </form>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Média Final</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Frequência</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Situação</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($resultado['linhas'] as $linha)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $linha['matricula']->aluno?->pessoa?->nome ?? 'Matrícula '.$linha['matricula']->id }}</td>
                        <td class="px-4 py-2 text-center font-semibold {{ $linha['media'] !== null && $linha['media'] >= $resultado['media_aprovacao'] ? 'text-green-600' : ($linha['media'] !== null ? 'text-red-600' : 'text-gray-400') }}">
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
    <div class="bg-white rounded-xl border p-8 text-center text-gray-400">Selecione turma e disciplina para calcular o boletim.</div>
    @endif
</div>
@endsection
