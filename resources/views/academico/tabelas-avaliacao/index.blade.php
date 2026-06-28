@extends('layouts.app')
@section('title', 'Tabelas de Avaliação')

@section('content')
<x-data-table title="Tabela de Avaliação" codigo="5" :createRoute="route('academico.tabelas-avaliacao.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nota Máxima</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Média Aprovação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Itens</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($tabelas as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $t->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $t->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($t->nota_maxima, 1, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($t->media_aprovacao, 1, ',', '.') }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $t->itens_count }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.tabelas-avaliacao.edit', $t) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.tabelas-avaliacao.destroy', $t) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma tabela cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $tabelas->links() }}</div>
</x-data-table>
@endsection
