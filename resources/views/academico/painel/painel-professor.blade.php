@extends('layouts.app')
@section('title', 'Painel do Professor')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">257</span>
            <h1 class="text-lg font-semibold text-gray-800">Painel do Professor</h1>
        </div>
        <form method="GET" action="{{ route('academico.painel-professor.index') }}" class="flex gap-2 items-end max-w-xl">
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
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Média</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Presenças</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Faltas</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Frequência</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($linhas as $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $l['aluno'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l['media'] !== null ? number_format($l['media'], 2, ',', '.') : '—' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l['presencas'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l['faltas'] }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $l['frequencia'] !== null ? $l['frequencia'].'%' : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum aluno matriculado nesta turma montada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
