@extends('layouts.app')
@section('title', 'Tabelas de Avaliação')

@section('content')
<x-data-table title="Tabela de Avaliação" codigo="5" :createRoute="route('academico.tabelas-avaliacao.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $t->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $t->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $t->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($t->nota_maxima, 1, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($t->media_aprovacao, 1, ',', '.') }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $t->itens_count }}</span></td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.tabelas-avaliacao.edit', $t)" :delete="route('academico.tabelas-avaliacao.destroy', $t)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma tabela cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $tabelas->links() }}</div>
</x-data-table>
@endsection
