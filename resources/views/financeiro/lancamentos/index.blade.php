@extends('layouts.app')
@section('title', 'Lançamentos Financeiros')

@php
$badges = ['entrada' => 'bg-green-100 text-green-700', 'saida' => 'bg-red-100 text-red-700', 'transferencia' => 'bg-blue-100 text-blue-700'];
@endphp

@section('content')
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-green-600">R$ {{ number_format($totalEntradas, 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Entradas</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-red-600">R$ {{ number_format($totalSaidas, 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Saídas</div></div>
    <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-gray-800">R$ {{ number_format($totalEntradas - $totalSaidas, 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Saldo</div></div>
</div>

<x-data-table title="Lançamentos Financeiros" codigo="61" :createRoute="route('financeiro.lancamentos.create')">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="py-3 px-3 w-10"></th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Conta</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Acoes</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($lancamentos as $l)
            <tr class="hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $l->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                <td class="px-4 py-3 text-gray-500">{{ $l->data_lancamento?->format('d/m/Y') }}</td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $l->descricao }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $l->contaBancaria?->nome ?? '—' }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $badges[$l->tipo] ?? 'bg-gray-100' }}">{{ $l->tipo }}</span></td>
                <td class="px-4 py-3 font-medium {{ $l->tipo === 'saida' ? 'text-red-600' : 'text-green-600' }}">R$ {{ number_format($l->valor, 2, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <x-kebab :edit="route('financeiro.lancamentos.edit', $l)" :delete="route('financeiro.lancamentos.destroy', $l)" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Nenhum lançamento registrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $lancamentos->links() }}</div>
</x-data-table>
@endsection
