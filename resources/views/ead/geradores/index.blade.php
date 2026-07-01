@extends('layouts.app')
@section('title', 'Gerador de Avaliações')

@section('content')
<x-data-table title="Gerador de Avaliações" codigo="241" :createRoute="route('ead.geradores.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Parâmetros</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($geradores as $g)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $g->descricao }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $g->parametros_count }} grupo(s) de questões</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('ead.geradores.edit', $g) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ead.geradores.destroy', $g) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Nenhum gerador cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $geradores->links() }}</div>
</x-data-table>
@endsection
