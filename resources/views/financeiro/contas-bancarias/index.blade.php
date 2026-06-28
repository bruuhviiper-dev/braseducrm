@extends('layouts.app')
@section('title', 'Contas Bancárias')

@section('content')
<x-data-table title="Contas Bancárias" codigo="63" :createRoute="route('financeiro.contas-bancarias.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Banco</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Agência / Conta</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Saldo Inicial</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Situacao</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($contas as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->nome }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->banco ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->agencia ?? '—' }} / {{ $c->conta ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-800">R$ {{ number_format($c->saldo_inicial, 2, ',', '.') }}</td>
                <td class="px-4 py-3">
                    @if($c->ativo)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Ativa</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">Inativa</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('financeiro.contas-bancarias.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('financeiro.contas-bancarias.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhuma conta cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $contas->links() }}</div>
</x-data-table>
@endsection
