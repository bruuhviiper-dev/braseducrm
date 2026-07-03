@extends('layouts.app')
@section('title', 'Categorias de Estoque')

@section('content')
<x-data-table title="Categorias de Estoque" codigo="149" :createRoute="route('estoque.categorias.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($categorias as $c)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('estoque.categorias.edit', $c)" :delete="route('estoque.categorias.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma categoria cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $categorias->links() }}</div>
</x-data-table>
@endsection
