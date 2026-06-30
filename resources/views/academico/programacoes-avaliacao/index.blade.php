@extends('layouts.app')
@section('title', 'Programações de Avaliações')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">4</span>
        <h1 class="text-lg font-semibold text-gray-800">Programações de Avaliações</h1>
    </div>
    <div class="p-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turma Montada</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tabela de Avaliação</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($combos as $c)
                @php $prog = $programacoes->get($c->turma_montada_id.'-'.$c->disciplina_id); @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $c->turma_nome ?? 'Turma #'.$c->turma_montada_id }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $c->disciplina_nome }}</td>
                    <td class="px-4 py-3">
                        @if($prog)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $prog->tabelaAvaliacao?->nome ?? 'definida' }}</span>
                        @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Não programada</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('academico.programacoes-avaliacao.editar', [$c->turma_montada_id, $c->disciplina_id]) }}" class="px-2.5 py-1 bg-primary-600 text-white rounded text-xs hover:bg-primary-700"><i class="fa-solid fa-calendar-check mr-1"></i> Programar</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma turma montada com disciplinas. Faça a Montagem de Turma primeiro.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $combos->links() }}</div>
    </div>
</div>
@endsection
