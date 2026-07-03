@extends('layouts.app')
@section('title', 'Cadastro de Salas')

@section('content')
<x-data-table title="Cadastro de Salas" codigo="202" :createRoute="route('academico.salas.create')">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50">
                <tr>
                <th class="py-3 px-3 w-10"></th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Capacidade</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bloco</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($salas as $sala)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $sala->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-600">{{ $sala->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $sala->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $sala->capacidade ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $sala->bloco ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($sala->ativo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <x-kebab :edit="route('academico.salas.edit', $sala)" :delete="route('academico.salas.destroy', $sala)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Nenhuma sala encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $salas->links() }}
    </div>
</x-data-table>
@endsection
