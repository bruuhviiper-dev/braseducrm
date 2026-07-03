@extends('layouts.app')
@section('title', 'Manutenção de Matrículas EAD')

@section('content')
<x-data-table title="Manutenção de Matrículas EAD" codigo="156" :createRoute="route('ead.matriculas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso EAD</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($matriculas as $m)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $m->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->cursoEad?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $m->situacao === 'ativa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $m->situacao }}</span></td>
                <td class="px-4 py-3 text-gray-600">{{ $m->ativo ? 'Sim' : 'Não' }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.matriculas.edit', $m)" :delete="route('ead.matriculas.destroy', $m)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma matrícula EAD.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $matriculas->links() }}</div>
</x-data-table>
@endsection
