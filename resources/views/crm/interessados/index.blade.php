@extends('layouts.app')
@section('title', 'Interessados')

@section('content')
<x-data-table title="Interessados" codigo="108" :createRoute="route('crm.interessados.create')" createLabel="Novo Interessado">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b">
                <th class="py-3 px-3 w-10"></th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Nome</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Email</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Telefone</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Origem</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Categoria</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Curso</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Ativo</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($interessados as $interessado)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $interessado->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                    <td class="px-4 py-3 text-gray-500">{{ $interessado->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $interessado->nome }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $interessado->email ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $interessado->telefone ?? $interessado->celular ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $interessado->origemInteressado->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $interessado->categoriaInteressado->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $interessado->curso->nome ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($interessado->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-kebab :edit="route('crm.interessados.edit', $interessado)" :delete="route('crm.interessados.destroy', $interessado)" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-8 text-center text-gray-400">
                        <i class="fa-solid fa-users text-3xl mb-2"></i>
                        <p>Nenhum interessado encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($interessados->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $interessados->links() }}
    </div>
    @endif
</x-data-table>
@endsection
