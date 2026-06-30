@extends('layouts.app')
@section('title', 'Cadastro de Avaliações EAD')

@section('content')
<x-data-table title="Cadastro de Avaliações EAD" codigo="214" :createRoute="route('ead.avaliacoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Curso EAD</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nota Mínima</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tentativas</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($avaliacoes as $a)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $a->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->cursoEad?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($a->nota_minima, 2, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $a->tentativas }}</td>
                <td class="px-4 py-3">
                    @if($a->ativo)<span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else<span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>@endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('ead.avaliacoes.edit', $a) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('ead.avaliacoes.destroy', $a) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma avaliação EAD.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $avaliacoes->links() }}</div>
</x-data-table>
@endsection
