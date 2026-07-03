@extends('layouts.app')
@section('title', 'Modelos de Documento')

@section('content')
<x-data-table title="Modelos de Documento" codigo="9" :createRoute="route('geral.modelos-documento.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($modelos as $m)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $m->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $m->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \App\Models\ModeloDocumento::TIPOS[$m->tipo] ?? $m->tipo }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs {{ $m->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $m->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('geral.modelos-documento.edit', $m)" :delete="route('geral.modelos-documento.destroy', $m)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum modelo cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $modelos->links() }}</div>
</x-data-table>
@endsection
