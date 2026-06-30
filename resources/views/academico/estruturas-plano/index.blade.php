@extends('layouts.app')
@section('title', 'Cadastro de Estrutura do Plano')

@section('content')
<x-data-table title="Cadastro de Estrutura do Plano" codigo="204" :createRoute="route('academico.estruturas-plano.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tópicos</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($estruturas as $e)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $e->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $e->topicos_count }} tópico(s)</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.estruturas-plano.edit', $e) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.estruturas-plano.destroy', $e) }}" onsubmit="return confirm('Remover esta estrutura?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Nenhuma estrutura cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $estruturas->links() }}</div>
</x-data-table>
@endsection
