@extends('layouts.app')
@section('title', 'Titulos a Receber')

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
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">64</span>
            <h1 class="text-lg font-semibold text-gray-800">Titulos a Receber</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('financeiro.titulos-receber.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Titulo
            </a>
        </div>
    </div>

    <div class="p-4">
        {{-- Filters --}}
        <form method="GET" action="{{ route('financeiro.titulos-receber.index') }}" class="mb-4">
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
                        <option value="renegociado" {{ request('situacao') == 'renegociado' ? 'selected' : '' }}>Renegociado</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" placeholder="Data Inicio"
                           class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                </div>
                <div>
                    <input type="date" name="data_fim" value="{{ request('data_fim') }}" placeholder="Data Fim"
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
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Pessoa</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Categoria</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Valor Original</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Desconto</th>
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
                        <td class="py-3 px-4 text-gray-500">{{ $titulo->id }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $titulo->pessoa->nome ?? '-' }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $titulo->categoriaReceber->nome ?? '-' }}</td>
                        <td class="py-3 px-4 text-right text-gray-800">R$ {{ number_format($titulo->valor_original, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">R$ {{ number_format($titulo->valor_desconto ?? 0, 2, ',', '.') }}</td>
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
                                @case('renegociado')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Renegociado</span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">{{ $titulo->situacao }}</span>
                            @endswitch
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                @if($titulo->situacao === 'aberto')
                                <form method="POST" action="{{ route('financeiro.titulos-receber.baixar', $titulo) }}" class="inline"
                                      onsubmit="return confirm('Confirma a baixa deste titulo?')">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded" title="Baixar (marcar como pago)">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('financeiro.titulos-receber.edit', $titulo) }}" class="p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded" title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('financeiro.titulos-receber.destroy', $titulo) }}" class="inline"
                                      onsubmit="return confirm('Deseja realmente excluir este titulo?')">
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
                        <td colspan="10" class="py-8 text-center text-gray-400">
                            <i class="fa-solid fa-file-invoice-dollar text-3xl mb-2"></i>
                            <p>Nenhum titulo a receber encontrado.</p>
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
<x-fab :route="route('financeiro.titulos-receber.create')" />
@endsection