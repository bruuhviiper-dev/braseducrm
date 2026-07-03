@extends('layouts.app')
@section('title', 'Gerador de Avaliações')

@section('content')
<x-data-table title="Gerador de Avaliações" codigo="241" :createRoute="route('ead.geradores.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Parâmetros</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($geradores as $g)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $g->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $g->descricao }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $g->parametros_count }} grupo(s) de questões</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('ead.geradores.edit', $g)" :delete="route('ead.geradores.destroy', $g)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum gerador cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $geradores->links() }}</div>
</x-data-table>
@endsection
