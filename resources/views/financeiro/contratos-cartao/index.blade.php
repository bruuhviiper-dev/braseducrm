@extends('layouts.app')
@section('title', 'Contratos de Cartões')

@section('content')
<x-data-table title="Contratos de Cartões" codigo="70" :createRoute="route('financeiro.contratos-cartao.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
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
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $c->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $c->operadora }}<span class="block text-xs text-gray-400">{{ $c->descricao }}</span></td>
                <td class="px-4 py-3 text-gray-600">{{ $c->contaBancaria?->nome ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_debito, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_credito_vista, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ number_format($c->taxa_credito_parcelado, 2, ',', '.') }}%</td>
                <td class="px-4 py-3 text-gray-600">{{ $c->prazo_recebimento_dias }}d</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('financeiro.contratos-cartao.edit', $c)" :delete="route('financeiro.contratos-cartao.destroy', $c)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum contrato cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $contratos->links() }}</div>
</x-data-table>
@endsection
