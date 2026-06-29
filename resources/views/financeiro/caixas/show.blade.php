@extends('layouts.app')
@section('title', 'Caixa #' . $caixa->id)

@php
$badges = ['entrada' => 'bg-green-100 text-green-700', 'suprimento' => 'bg-green-100 text-green-700', 'saida' => 'bg-red-100 text-red-700', 'sangria' => 'bg-red-100 text-red-700'];
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-lg font-semibold text-gray-800">Caixa #{{ $caixa->id }} <span class="text-sm font-normal text-gray-400">— {{ $caixa->contaBancaria?->nome ?? 'Caixa interno' }}</span></h1>
        <a href="{{ route('financeiro.caixas.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
    </div>

    {{-- Resumo --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border p-4"><div class="text-lg font-bold text-gray-800">R$ {{ number_format($caixa->valor_abertura, 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Abertura</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-lg font-bold text-green-600">R$ {{ number_format($caixa->saldoAtual(), 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Saldo atual</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-sm font-medium {{ $caixa->situacao === 'aberto' ? 'text-green-600' : 'text-gray-500' }} capitalize">{{ $caixa->situacao }}</div><div class="text-xs text-gray-500 mt-1">Situação</div></div>
        <div class="bg-white rounded-xl border p-4 flex items-center">
            @if($caixa->situacao === 'aberto')
            <form method="POST" action="{{ route('financeiro.caixas.fechar', $caixa) }}" onsubmit="return confirm('Fechar o caixa? Esta ação é definitiva.')" class="w-full">
                @csrf
                <button class="w-full px-3 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-lock mr-1"></i> Fechar Caixa</button>
            </form>
            @else
            <div class="text-xs text-gray-400">Fechado em {{ $caixa->data_fechamento?->format('d/m/Y H:i') }}</div>
            @endif
        </div>
    </div>

    {{-- Nova movimentação --}}
    @if($caixa->situacao === 'aberto')
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b"><h2 class="text-sm font-semibold text-gray-700">Nova movimentação</h2></div>
        <form method="POST" action="{{ route('financeiro.caixas.movimentar', $caixa) }}" class="p-4 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                <select name="tipo" class="w-full border rounded-lg px-2 py-2 text-sm">
                    <option value="entrada">Entrada</option>
                    <option value="saida">Saída</option>
                    <option value="suprimento">Suprimento</option>
                    <option value="sangria">Sangria</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Valor</label>
                <input type="number" step="0.01" min="0.01" name="valor" class="w-full border rounded-lg px-2 py-2 text-sm" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Descrição</label>
                <input type="text" name="descricao" class="w-full border rounded-lg px-2 py-2 text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Forma</label>
                <div class="flex gap-2">
                    <select name="forma_pagamento" class="w-full border rounded-lg px-2 py-2 text-sm">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="pix">PIX</option>
                        <option value="cartao_debito">Cartão Déb.</option>
                        <option value="cartao_credito">Cartão Créd.</option>
                        <option value="cheque">Cheque</option>
                    </select>
                    <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>
        </form>
    </div>
    @endif

    {{-- Movimentações --}}
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b"><h2 class="text-sm font-semibold text-gray-700">Movimentações</h2></div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Data</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Tipo</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Forma</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Valor</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($caixa->movimentacoes as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $m->created_at?->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full capitalize {{ $badges[$m->tipo] ?? 'bg-gray-100' }}">{{ $m->tipo }}</span></td>
                    <td class="px-4 py-3 text-gray-800">{{ $m->descricao }}</td>
                    <td class="px-4 py-3 text-gray-500 capitalize">{{ str_replace('_', ' ', $m->forma_pagamento) }}</td>
                    <td class="px-4 py-3 font-medium {{ in_array($m->tipo, ['saida','sangria']) ? 'text-red-600' : 'text-green-600' }}">R$ {{ number_format($m->valor, 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma movimentação.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
