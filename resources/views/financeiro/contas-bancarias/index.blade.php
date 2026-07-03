@extends('layouts.app')
@section('title', 'Contas Bancárias')

@section('content')
<x-data-table title="Contas Bancárias" codigo="63" :createRoute="route('financeiro.contas-bancarias.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
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
                    <x-kebab :edit="route('financeiro.contas-bancarias.edit', $c)" :delete="route('financeiro.contas-bancarias.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhuma conta cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $contas->links() }}</div>
</x-data-table>
@endsection
