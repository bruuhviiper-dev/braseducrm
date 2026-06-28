@extends('layouts.app')
@section('title', 'Publicações do Portal')

@section('content')
<x-data-table title="Publicações Portal Aluno" codigo="77" :createRoute="route('portais.publicacoes.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Pasta</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Publicado em</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($publicacoes as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $p->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $p->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->pasta?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->publicado_em?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($p->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('portais.publicacoes.edit', $p) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('portais.publicacoes.destroy', $p) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma publicação cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $publicacoes->links() }}</div>
</x-data-table>
@endsection
