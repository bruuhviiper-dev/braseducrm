@extends('layouts.app')
@section('title', 'Finalizar Turma')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.montagem-turma.edit', $turmaMontada) }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">41</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Finalizar Turma — {{ $turmaMontada->nome ?? $turmaMontada->sigla ?? ('#' . $turmaMontada->id) }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Turmas › Encerramento do período letivo</p>
            </div>
        </div>

        <form method="POST" action="{{ route('academico.montagem-turma.processar-finalizacao', $turmaMontada) }}" class="p-6 space-y-5">
            @csrf

            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4 text-sm text-cyan-800">
                <p class="font-semibold mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Como funciona</p>
                <p>O sistema varre os boletins e <strong>aprova automaticamente</strong> os alunos que atingiram a média mínima
                ({{ number_format($linhas[0]['media_aprovacao'] ?? 6, 1, ',', '.') }}) e a frequência mínima
                ({{ number_format($linhas[0]['frequencia_minima'] ?? 75, 0) }}%). Para os retidos, defina o destino da matrícula.
                Alunos com mensalidades em atraso são sinalizados e, na renovação automática, entram como <strong>Não Confirmados</strong>.</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-center">Média</th>
                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-center">Frequência</th>
                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase text-center">Financeiro</th>
                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Resultado / Destino</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($linhas as $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 font-medium text-gray-800">{{ $l['matricula']->aluno?->pessoa?->nome ?? '—' }}</td>
                        <td class="px-3 py-2 text-center {{ $l['media'] !== null && $l['media'] < $l['media_aprovacao'] ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $l['media'] !== null ? number_format($l['media'], 2, ',', '.') : '—' }}</td>
                        <td class="px-3 py-2 text-center {{ $l['frequencia'] !== null && $l['frequencia'] < $l['frequencia_minima'] ? 'text-red-600 font-semibold' : 'text-gray-700' }}">{{ $l['frequencia'] !== null ? number_format($l['frequencia'], 1, ',', '.') . '%' : '—' }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($l['inadimplente'])
                            <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">Inadimplente</span>
                            @else
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Em dia</span>
                            @endif
                        </td>
                        <td class="px-3 py-2">
                            @if($l['aprovado'])
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold"><i class="fa-solid fa-check mr-1"></i>Aprovado</span>
                            @else
                            <select name="destinos[{{ $l['matricula']->id }}]" class="border rounded-lg px-2 py-1.5 text-sm w-full">
                                <option value="dependencia">Dependência (DP)</option>
                                <option value="recuperacao">Turma de Recuperação</option>
                                <option value="trancada">Trancado</option>
                                <option value="desistente">Desistente</option>
                                <option value="cancelada">Cancelado</option>
                            </select>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">Nenhum aluno ativo nesta turma montada.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t pt-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Ações coletivas de renovação</p>
                <label class="block text-sm font-medium text-gray-700 mb-1">Matricular os aprovados na turma do módulo seguinte (opcional)</label>
                <select name="turma_destino_id" class="w-full md:w-96 border rounded-lg px-3 py-2 text-sm">
                    <option value="">Não matricular automaticamente</option>
                    @foreach($turmasDestino as $td)
                    <option value="{{ $td->id }}">{{ $td->nome ?? $td->sigla ?? ('#'.$td->id) }} {{ $td->turma ? '— '.$td->turma->nome : '' }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Alunos inadimplentes entram na nova turma com matrícula "Não Confirmada" até regularizarem o financeiro.</p>
            </div>

            <div class="flex justify-end items-center gap-3 pt-4 sticky bottom-4 z-10">
                <a href="{{ route('academico.montagem-turma.edit', $turmaMontada) }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" onclick="return confirm('Finalizar a turma? Esta ação processa o encerramento do período letivo em lote.')" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-flag-checkered mr-1"></i> Finalizar Turma</button>
            </div>
        </form>
    </div>
</div>
@endsection
