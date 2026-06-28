@extends('layouts.app')
@section('title', 'Grupos de Operadores')

@section('content')
<x-data-table title="Grupo de Operadores" codigo="43" :createRoute="route('admin.grupos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operadores</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Permissões</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($grupos as $g)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $g->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $g->nome }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $g->users_count }}</span></td>
                <td class="px-4 py-3"><span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">{{ $g->funcoes_count }} funções</span></td>
                <td class="px-4 py-3">
                    @if($g->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('admin.grupos.edit', $g) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('admin.grupos.destroy', $g) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum grupo cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $grupos->links() }}</div>
</x-data-table>
@endsection
