@extends('layouts.app')
@section('title', 'Aberturas de Matrícula Online')

@section('content')
<x-data-table title="Abertura de Matrícula Online" codigo="140" :createRoute="route('matricula-online.aberturas.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Período</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Vagas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Inscrições</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($aberturas as $a)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $a->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $a->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->curso?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->data_inicio->format('d/m/Y') }} a {{ $a->data_fim->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->vagas ?? '∞' }}</td>
                <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $a->inscricoes_count }}</span></td>
                <td class="px-4 py-3">
                    @if($a->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativa</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativa</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('matricula-online.aberturas.edit', $a) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('matricula-online.aberturas.destroy', $a) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma abertura cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $aberturas->links() }}</div>
</x-data-table>
@endsection
