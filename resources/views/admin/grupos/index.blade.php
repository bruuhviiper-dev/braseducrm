@extends('layouts.app')
@section('title', 'Grupos de Operadores')

@section('content')
<x-data-table title="Grupo de Operadores" codigo="43" :createRoute="route('admin.grupos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $g->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
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
                    <x-kebab :edit="route('admin.grupos.edit', $g)" :delete="route('admin.grupos.destroy', $g)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum grupo cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $grupos->links() }}</div>
</x-data-table>
@endsection
