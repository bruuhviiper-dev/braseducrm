@extends('layouts.app')
@section('title', 'Campanhas de Indicação')

@section('content')
<x-data-table title="Campanhas de Indicação" codigo="225" :createRoute="route('geral.campanhas-indicacao.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Período</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Indicações</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ativo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($campanhas as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ optional($c->data_inicio)->format('d/m/y') ?? '—' }} — {{ optional($c->data_fim)->format('d/m/y') ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->indicacoes_count }}</td>
                <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-xs {{ $c->ativo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $c->ativo ? 'Ativa' : 'Inativa' }}</span></td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('geral.campanhas-indicacao.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('geral.campanhas-indicacao.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma campanha cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $campanhas->links() }}</div>
</x-data-table>
@endsection
