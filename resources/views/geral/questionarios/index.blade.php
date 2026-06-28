@extends('layouts.app')
@section('title', 'Cadastro de Questionário')

@php $tipos = \App\Models\Questionario::tipos(); @endphp

@section('content')
<x-data-table title="Cadastro de Questionário" codigo="34" :createRoute="route('geral.questionarios.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Questões</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($questionarios as $q)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $q->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $q->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $tipos[$q->tipo] ?? $q->tipo }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $q->questoes_count }}</span></td>
                <td class="px-4 py-3">
                    @if($q->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('geral.questionarios.responder', $q) }}" target="_blank" class="p-1.5 text-green-600 hover:bg-green-50 rounded" title="Responder"><i class="fa-solid fa-pen-nib"></i></a>
                        <a href="{{ route('geral.questionarios.resultados', $q) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded" title="Resultados"><i class="fa-solid fa-chart-simple"></i></a>
                        <a href="{{ route('geral.questionarios.edit', $q) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('geral.questionarios.destroy', $q) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum questionário cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $questionarios->links() }}</div>
</x-data-table>
@endsection
