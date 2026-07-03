@extends('layouts.app')
@section('title', 'Movimentacoes de Estoque')

@section('content')
<x-data-table title="Movimentacoes de Estoque" codigo="151" :createRoute="route('estoque.movimentacoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Produto</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Deposito</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Qtd</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor Unit.</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($movimentacoes as $m)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $m->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $m->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $m->produtoEstoque->nome ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->deposito->nome ?? '-' }}</td>
                <td class="px-4 py-3">
                    @php $cores = ['entrada' => 'bg-green-100 text-green-700', 'saida' => 'bg-red-100 text-red-700', 'transferencia' => 'bg-blue-100 text-blue-700'] @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $cores[$m->tipo] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($m->tipo) }}</span>
                </td>
                <td class="px-4 py-3 text-gray-800">{{ number_format($m->quantidade, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->valor_unitario ? 'R$ ' . number_format($m->valor_unitario, 2, ',', '.') : '-' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $m->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('estoque.movimentacoes.edit', $m)" :delete="route('estoque.movimentacoes.destroy', $m)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">Nenhuma movimentacao registrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $movimentacoes->links() }}</div>
</x-data-table>
@endsection
