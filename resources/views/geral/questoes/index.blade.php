@extends('layouts.app')
@section('title', 'Cadastro de Questões')

@php $tipos = \App\Models\Questao::tipos(); @endphp

@section('content')
<x-data-table title="Cadastro de Questões" codigo="33" :createRoute="route('geral.questoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Enunciado</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Opções</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($questoes as $q)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $q->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ \Illuminate\Support\Str::limit($q->enunciado, 70) }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $tipos[$q->tipo] ?? $q->tipo }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $q->opcoes_count }}</span></td>
                <td class="px-4 py-3">
                    @if($q->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativa</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativa</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('geral.questoes.edit', $q) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('geral.questoes.destroy', $q) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma questão cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $questoes->links() }}</div>
</x-data-table>
@endsection
