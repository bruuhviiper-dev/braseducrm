@extends('layouts.app')
@section('title', 'Templates de Mensagens')

@section('content')
<x-data-table title="Templates de Mensagens" codigo="87" :createRoute="route('comunicacao.templates.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Canal</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($templates as $t)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $t->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $t->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $t->nome }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">{{ ucfirst($t->tipo) }}</span></td>
                <td class="px-4 py-3 text-gray-600">{{ ucfirst($t->canal) }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-0.5 rounded text-xs {{ $t->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $t->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('comunicacao.templates.edit', $t)" :delete="route('comunicacao.templates.destroy', $t)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum template cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $templates->links() }}</div>
</x-data-table>
@endsection
