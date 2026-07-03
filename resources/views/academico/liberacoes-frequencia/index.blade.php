@extends('layouts.app')
@section('title', 'Liberar Lançamento de Frequência')

@section('content')
<x-data-table title="Liberar Lançamento de Frequência" codigo="262" :createRoute="route('academico.liberacoes-frequencia.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turma Montada</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Professor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Início Liberação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Fim Liberação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $r->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->turmaMontada?->nome ?? $r->turmaMontada?->turma?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->profissional?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->data_inicio?->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->data_fim?->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.liberacoes-frequencia.edit', $r)" :delete="route('academico.liberacoes-frequencia.destroy', $r)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma liberação cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
