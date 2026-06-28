@extends('layouts.app')
@section('title', 'Montagem de Turma e Horário')

@php
$badges = [
    'aberta' => 'bg-blue-100 text-blue-700',
    'em_andamento' => 'bg-amber-100 text-amber-700',
    'finalizada' => 'bg-green-100 text-green-700',
];
@endphp

@section('content')
<x-data-table title="Montagem de Turma e Horário" codigo="41" :createRoute="route('academico.montagem-turma.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Turma</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Módulo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Período</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Horários</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($turmasMontadas as $tm)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $tm->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $tm->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $tm->turma?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $tm->modulo?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $tm->periodoLetivo?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $tm->horarios_count }}</span></td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $badges[$tm->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ str_replace('_',' ',$tm->situacao) }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.montagem-turma.edit', $tm) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.montagem-turma.destroy', $tm) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma turma montada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $turmasMontadas->links() }}</div>
</x-data-table>
@endsection
