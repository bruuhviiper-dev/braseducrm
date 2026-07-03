@extends('layouts.app')
@section('title', 'Cadastro de Estrutura do Plano')

@section('content')
<x-data-table title="Cadastro de Estrutura do Plano" codigo="204" :createRoute="route('academico.estruturas-plano.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tópicos</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($estruturas as $e)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $e->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $e->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $e->topicos_count }} tópico(s)</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('academico.estruturas-plano.edit', $e)" :delete="route('academico.estruturas-plano.destroy', $e)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma estrutura cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $estruturas->links() }}</div>
</x-data-table>
@endsection
