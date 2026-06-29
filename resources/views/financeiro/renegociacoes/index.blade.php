@extends('layouts.app')
@section('title', 'Renegociações')

@section('content')
<x-data-table title="Renegociações de Parcelas" codigo="80" :createRoute="route('financeiro.renegociacoes.create')" createLabel="Nova Renegociação">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor Original</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor Renegociado</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Parcelas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($renegociacoes as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $r->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($r->valor_total_original, 2, ',', '.') }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">R$ {{ number_format($r->valor_total_renegociado, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->numero_parcelas }}x</td>
                <td class="px-4 py-3 text-gray-500">{{ $r->data_renegociacao?->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma renegociação registrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $renegociacoes->links() }}</div>
</x-data-table>
@endsection
