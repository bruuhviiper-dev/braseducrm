@extends('layouts.app')
@section('title', 'Titulos a Pagar')

@section('content')
{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-clock text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total em Aberto</p>
                <p class="text-lg font-bold text-blue-600">R$ {{ number_format($totalAberto, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Pago</p>
                <p class="text-lg font-bold text-green-600">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Vencido</p>
                <p class="text-lg font-bold text-red-600">R$ {{ number_format($totalVencido, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-ban text-gray-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">Total Cancelado</p>
                <p class="text-lg font-bold text-gray-500">R$ {{ number_format($totalCancelado, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">52</span>
            <h1 class="text-lg font-semibold text-gray-800">Titulos a Pagar</h1>
        </div>
    </div>

    <div class="p-4">
        {{-- Filters --}}
        <form method="GET" action="{{ route('financeiro.titulos-pagar.index') }}" class="mb-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Buscar por nome da pessoa..."
                           class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
                <div>
                    <select name="situacao" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Todas Situacoes</option>
                        <option value="aberto" {{ request('situacao') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                        <option value="pago" {{ request('situacao') == 'pago' ? 'selected' : '' }}>Pago</option>
                        <option value="cancelado" {{ request('situacao') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}"
                           class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}"
                           class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 border rounded-lg text-sm text-gray-600 hover:bg-gray-200 transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrar
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                <th class="py-3 px-3 w-10"></th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Pessoa</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Categoria</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Descricao</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Valor Original</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Valor Pago</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Vencimento</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Pagamento</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Situacao</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($titulos as $titulo)
                    @php
                        $vencido = $titulo->situacao === 'aberto' && $titulo->data_vencimento && $titulo->data_vencimento->isPast();
                        $situacaoLabel = $vencido ? 'vencido' : $titulo->situacao;
                    @endphp
                    <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-3"><input type="radio" name="sel" value="{{ $titulo->id }}" class="w-4 h-4 text-primary-600 border-gray-300"></td>
                        <td class="py-3 px-4 text-gray-500">{{ $titulo->id }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $titulo->pessoa->nome ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $titulo->categoriaPagar->nome ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ Str::limit($titulo->descricao ?? '-', 30) }}</td>
                        <td class="py-3 px-4 text-right text-gray-800">R$ {{ number_format($titulo->valor_original, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right text-gray-800">R$ {{ number_format($titulo->valor_pago ?? 0, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $titulo->data_vencimento ? $titulo->data_vencimento->format('d/m/Y') : '-' }}</td>
                        <td class="py-3 px-4 text-center text-gray-600">{{ $titulo->data_pagamento ? $titulo->data_pagamento->format('d/m/Y') : '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            @switch($situacaoLabel)
                                @case('aberto')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Aberto</span>
                                    @break
                                @case('pago')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Pago</span>
                                    @break
                                @case('cancelado')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Cancelado</span>
                                    @break
                                @case('vencido')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Vencido</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $titulo->situacao }}</span>
                            @endswitch
                        </td>
                        <td class="py-3 px-4 text-center">
                            <x-kebab :edit="route('financeiro.titulos-pagar.edit', $titulo)" :delete="route('financeiro.titulos-pagar.destroy', $titulo)" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-8 text-center text-gray-400">
                            <i class="fa-solid fa-file-invoice text-3xl mb-2"></i>
                            <p>Nenhum titulo a pagar encontrado.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $titulos->links() }}
        </div>
    </div>
</div>
<x-fab :route="route('financeiro.titulos-pagar.create')" />
@endsection