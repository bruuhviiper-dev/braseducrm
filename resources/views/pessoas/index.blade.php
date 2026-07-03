@extends('layouts.app')
@section('title', 'Pessoas')

@section('content')
<x-data-table title="Cadastro de Pessoa" codigo="11" breadcrumb="Geral › Pessoas" :createRoute="route('pessoas.create')" createLabel="Nova Pessoa">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">ID</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Nome</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">CPF/CNPJ</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Email</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Telefone</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-600">Cidade/UF</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Ativo</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-600">Acoes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pessoas as $pessoa)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">{{ $pessoa->id }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('pessoas.show', $pessoa) }}" class="text-primary-600 hover:underline font-medium">
                            {{ $pessoa->nome }}
                        </a>
                    </td>
                    <td class="py-3 px-4 text-gray-600">{{ $pessoa->cpf ?? $pessoa->cnpj ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pessoa->email ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pessoa->telefone ?? $pessoa->celular ?? '-' }}</td>
                    <td class="py-3 px-4 text-gray-600">
                        @if($pessoa->cidade)
                            {{ $pessoa->cidade }}{{ $pessoa->uf ? '/' . $pessoa->uf : '' }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($pessoa->ativo)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Sim</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Nao</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('pessoas.show', $pessoa) }}" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded" title="Visualizar">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('pessoas.edit', $pessoa) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('pessoas.destroy', $pessoa) }}" class="inline"
                                  onsubmit="return confirm('Deseja realmente excluir esta pessoa?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded" title="Excluir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-gray-400">
                        <i class="fa-solid fa-users text-3xl mb-2"></i>
                        <p>Nenhuma pessoa encontrada.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pessoas->links() }}
    </div>
</x-data-table>
@endsection
