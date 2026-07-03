@extends('layouts.app')
@section('title', 'Unidades de Medida')

@section('content')
<x-data-table title="Unidades de Medida" codigo="150" :createRoute="route('estoque.unidades.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($unidades as $u)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $u->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $u->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $u->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $u->sigla }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('estoque.unidades.edit', $u)" :delete="route('estoque.unidades.destroy', $u)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma unidade cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $unidades->links() }}</div>
</x-data-table>
@endsection
