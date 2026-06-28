@extends('layouts.app')
@section('title', 'Unidades de Medida')

@section('content')
<x-data-table title="Unidades de Medida" codigo="150" :createRoute="route('estoque.unidades.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($unidades as $u)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $u->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $u->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $u->sigla }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('estoque.unidades.edit', $u) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('estoque.unidades.destroy', $u) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma unidade cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $unidades->links() }}</div>
</x-data-table>
@endsection
