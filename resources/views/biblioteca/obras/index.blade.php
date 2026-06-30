@extends('layouts.app')
@section('title', 'Cadastro de Obra')

@section('content')
<x-data-table title="Cadastro de Obra" codigo="288" :createRoute="route('biblioteca.obras.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Autores</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Editor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Exemplares</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($obras as $o)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $o->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->autores->map(fn($a) => $a->nome_completo)->implode(', ') ?: '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->editor?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->tipoMaterial?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->exemplares_count }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('biblioteca.obras.edit', $o) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('biblioteca.obras.destroy', $o) }}" onsubmit="return confirm('Remover esta obra e seus exemplares?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhuma obra cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $obras->links() }}</div>
</x-data-table>
@endsection
