@extends('layouts.app')
@section('title', 'Controle de Rematrículas')

@php
$badge = fn($s) => match($s) { 'Confirmada' => 'bg-green-100 text-green-700', 'Cancelada' => 'bg-red-100 text-red-700', default => 'bg-amber-100 text-amber-700' };
@endphp

@section('content')
<x-data-table title="Controle de Rematrículas" codigo="279" :createRoute="route('academico.rematriculas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Futura Turma</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Abertura</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($registros as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $r->matricula?->rotulo ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->futuraTurma?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($r->data_abertura)->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $badge($r->situacao) }}">{{ $r->situacao }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.rematriculas.edit', $r) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.rematriculas.destroy', $r) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma rematrícula aberta.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $registros->links() }}</div>
</x-data-table>
@endsection
