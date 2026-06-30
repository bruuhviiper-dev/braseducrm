@extends('layouts.app')
@section('title', 'Planejamento Diário de Aulas')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">45</span>
            <h1 class="text-lg font-semibold text-gray-800">Planejamento Diário de Aulas</h1>
        </div>
        <form method="GET" action="{{ route('academico.planejamento-diario.index') }}" class="flex gap-2 items-end max-w-xl">
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Turma Montada</label>
                <select name="turma_montada_id" onchange="this.form.submit()" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $t)<option value="{{ $t->id }}" {{ (string)request('turma_montada_id') === (string)$t->id ? 'selected' : '' }}>{{ $t->nome ?? $t->turma?->nome ?? ('TM #'.$t->id) }}</option>@endforeach
                </select>
            </div>
        </form>
    </div>

    @if($tm)
    <div class="bg-white rounded-xl border">
        <div class="p-4">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Dia</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Início</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Fim</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Professor</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sala</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($aulas as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $a['dia'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a['inicio'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a['fim'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a['disciplina'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a['professor'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a['sala'] }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Esta turma montada não tem horários. Faça a Montagem de Turma.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
