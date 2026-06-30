@extends('layouts.app')
@section('title', 'Liberar Lançamento de Frequência')

@section('content')
<x-data-table title="Liberar Lançamento de Frequência" codigo="262" :createRoute="route('academico.liberacoes-frequencia.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
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
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->turmaMontada?->nome ?? $r->turmaMontada?->turma?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->profissional?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->data_inicio?->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->data_fim?->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.liberacoes-frequencia.edit', $r) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.liberacoes-frequencia.destroy', $r) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma liberação cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
