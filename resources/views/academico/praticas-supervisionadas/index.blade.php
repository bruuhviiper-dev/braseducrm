@extends('layouts.app')
@section('title', 'Controle de Prática Supervisionada')

@section('content')
<x-data-table title="Controle de Prática Supervisionada" codigo="90" :createRoute="route('academico.praticas-supervisionadas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disciplina</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Quantidade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->matricula?->rotulo ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->disciplina?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($r->quantidade, 2, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $r->situacao === 'Aprovado' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $r->situacao }}</span>
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.praticas-supervisionadas.edit', $r)" :delete="route('academico.praticas-supervisionadas.destroy', $r)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum lançamento.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
