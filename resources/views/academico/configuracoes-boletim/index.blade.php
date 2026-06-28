@extends('layouts.app')
@section('title', 'Configurações do Boletim')

@section('content')
<x-data-table title="Configuração do Boletim" codigo="3" :createRoute="route('academico.configuracoes-boletim.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Média Aprovação</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Freq. Mínima</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($configuracoes as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->media_aprovacao, 1, ',', '.') }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->frequencia_minima, 0) }}%</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('academico.configuracoes-boletim.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('academico.configuracoes-boletim.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma configuração cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $configuracoes->links() }}</div>
</x-data-table>
@endsection
