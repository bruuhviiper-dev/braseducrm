@extends('layouts.app')
@section('title', 'Cadastro de Obra')

@section('content')
<x-data-table title="Cadastro de Obra" codigo="288" :createRoute="route('biblioteca.obras.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $o->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $o->titulo }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->autores->map(fn($a) => $a->nome_completo)->implode(', ') ?: '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->editor?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->tipoMaterial?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->exemplares_count }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('biblioteca.obras.edit', $o)" :delete="route('biblioteca.obras.destroy', $o)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma obra cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $obras->links() }}</div>
</x-data-table>
@endsection
