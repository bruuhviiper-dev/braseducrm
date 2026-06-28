@extends('layouts.app')
@section('title', 'Diploma Digital')

@php
$tipos = \App\Models\DiplomaDigital::situacoes();
$badges = [
    'pendente' => 'bg-amber-100 text-amber-700',
    'emitido' => 'bg-blue-100 text-blue-700',
    'assinado' => 'bg-indigo-100 text-indigo-700',
    'registrado' => 'bg-green-100 text-green-700',
];
@endphp

@section('content')
<x-data-table title="Diploma Digital" codigo="215" :createRoute="route('ged.diplomas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nº Registro</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Emissão</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($diplomas as $d)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $d->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $d->aluno?->pessoa?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $d->curso?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $d->numero_registro ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $d->data_emissao?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $badges[$d->situacao] ?? 'bg-gray-100 text-gray-500' }}">{{ $tipos[$d->situacao] ?? $d->situacao }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('ged.diplomas.edit', $d) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ged.diplomas.destroy', $d) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum diploma cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $diplomas->links() }}</div>
</x-data-table>
@endsection
