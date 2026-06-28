@extends('layouts.app')
@section('title', 'Atendimentos')

@php
$badges = [
    'aberto' => 'bg-amber-100 text-amber-700',
    'em_andamento' => 'bg-blue-100 text-blue-700',
    'concluido' => 'bg-green-100 text-green-700',
    'falha' => 'bg-red-100 text-red-700',
];
$labels = [
    'aberto' => 'Aberto',
    'em_andamento' => 'Em andamento',
    'concluido' => 'Concluído',
    'falha' => 'Falha',
];
@endphp

@section('content')
<x-data-table title="Atendimentos" codigo="55" :createRoute="route('atendimentos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pessoa</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Categoria</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operador</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($atendimentos as $a)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $a->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $a->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->categoria?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->operador?->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $badges[$a->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ $labels[$a->situacao] ?? $a->situacao }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $a->created_at?->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('atendimentos.edit', $a) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('atendimentos.destroy', $a) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum atendimento registrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $atendimentos->links() }}</div>
</x-data-table>
@endsection
