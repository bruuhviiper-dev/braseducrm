@extends('layouts.app')
@section('title', 'Funis de Vendas')

@section('content')
<x-data-table title="Funis de Vendas" codigo="200" :createRoute="route('crm.funil.create')" createLabel="Novo Funil">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">ID</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Nome</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Etapas</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Oportunidades</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Padrao</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Ativo</th>
                    <th class="text-center px-4 py-3 font-semibold text-gray-600">Acoes</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($funis as $funil)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-500">{{ $funil->id }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('crm.funil.show', $funil) }}" class="font-medium text-primary-600 hover:text-primary-700 hover:underline">
                            {{ $funil->nome }}
                        </a>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                            {{ $funil->etapas_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                            {{ $funil->oportunidades_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($funil->padrao)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                <i class="fa-solid fa-star mr-1"></i> Sim
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($funil->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inativo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('crm.funil.show', $funil) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Visualizar Kanban">
                                <i class="fa-solid fa-columns"></i>
                            </a>
                            <a href="{{ route('crm.funil.edit', $funil) }}" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition" title="Editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form method="POST" action="{{ route('crm.funil.destroy', $funil) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este funil e todas suas etapas?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Remover">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        <i class="fa-solid fa-filter text-3xl mb-2"></i>
                        <p>Nenhum funil encontrado.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($funis->hasPages())
    <div class="px-4 py-3 border-t">
        {{ $funis->links() }}
    </div>
    @endif
</x-data-table>
@endsection
