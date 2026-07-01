@extends('layouts.app')
@section('title', 'Contratos de Cartões')

@section('content')
<x-data-table title="Contratos de Cartões" codigo="70" :createRoute="route('financeiro.contratos-cartao.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Operadora</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Conta</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Débito</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Créd. à vista</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Créd. parc.</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Prazo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($contratos as $c)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->operadora }}<span class="block text-xs text-gray-400">{{ $c->descricao }}</span></td>
                <td class="px-4 py-3 text-gray-600">{{ $c->contaBancaria?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_debito, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_credito_vista, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_credito_parcelado, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->prazo_recebimento_dias }}d</td>
                <td class="px-4 py-3">
                    <div class="flex gap-1">
                        <a href="{{ route('financeiro.contratos-cartao.edit', $c) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form method="POST" action="{{ route('financeiro.contratos-cartao.destroy', $c) }}" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum contrato cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $contratos->links() }}</div>
</x-data-table>
@endsection
