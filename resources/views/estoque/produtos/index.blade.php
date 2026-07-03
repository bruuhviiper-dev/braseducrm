@extends('layouts.app')
@section('title', 'Cadastro de Produtos de Estoque')

@section('content')
<x-data-table title="Cadastro de Produtos de Estoque" codigo="148" :createRoute="route('estoque.produtos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Codigo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Categoria</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Unidade</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Estoque</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Preco Custo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($produtos as $p)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $p->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $p->id }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->codigo ?? '-' }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $p->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->categoriaEstoque->nome ?? '-' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->unidadeMedida->sigla ?? '-' }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $p->estoque_atual <= $p->estoque_minimo ? 'text-red-600 font-semibold' : 'text-gray-800' }}">{{ $p->estoque_atual }}</span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $p->preco_custo ? 'R$ ' . number_format($p->preco_custo, 2, ',', '.') : '-' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $p->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $p->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('estoque.produtos.edit', $p)" :delete="route('estoque.produtos.destroy', $p)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">Nenhum produto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $produtos->links() }}</div>
</x-data-table>
@endsection
