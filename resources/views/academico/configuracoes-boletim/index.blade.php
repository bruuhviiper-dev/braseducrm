@extends('layouts.app')
@section('title', 'Configurações do Boletim')

@section('content')
<x-data-table title="Configuração do Boletim" codigo="3" :createRoute="route('academico.configuracoes-boletim.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Média Aprovação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Freq. Mínima</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($configuracoes as $c)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->media_aprovacao, 1, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->frequencia_minima, 0) }}%</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.configuracoes-boletim.edit', $c)" :delete="route('academico.configuracoes-boletim.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma configuração cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $configuracoes->links() }}</div>
</x-data-table>
@endsection
