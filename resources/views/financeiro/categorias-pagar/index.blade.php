@extends('layouts.app')
@section('title', 'Categorias a Pagar')

@section('content')
<x-data-table title="Categorias a Pagar" codigo="51" :createRoute="route('financeiro.categorias-pagar.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Plano de Contas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categorias as $c)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->planoConta?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('financeiro.categorias-pagar.edit', $c)" :delete="route('financeiro.categorias-pagar.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma categoria cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $categorias->links() }}</div>
</x-data-table>
@endsection
