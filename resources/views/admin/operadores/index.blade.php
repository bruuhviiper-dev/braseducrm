@extends('layouts.app')
@section('title', 'Operadores')

@section('content')
<x-data-table title="Cadastro de Operador" codigo="44" :createRoute="route('admin.operadores.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Login</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Grupo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Departamento</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($operadores as $o)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $o->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $o->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $o->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->login }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->grupoOperador->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $o->departamento->nome ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($o->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativo</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('admin.operadores.edit', $o)" :delete="route('admin.operadores.destroy', $o)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum operador cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $operadores->links() }}</div>
</x-data-table>
@endsection
