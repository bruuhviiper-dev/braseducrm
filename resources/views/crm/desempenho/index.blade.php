@extends('layouts.app')
@section('title', 'Desempenho de Consultores')

@section('content')
<div class="bg-white rounded-xl border mb-6">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">190</span>
            <h1 class="text-lg font-semibold text-gray-800">Desempenho de Consultores (CRM)</h1>
        </div>
        <button onclick="window.location.reload()" class="px-3 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition flex items-center gap-1">
            <i class="fa-solid fa-arrows-rotate"></i> Atualizar
        </button>
    </div>
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-handshake text-blue-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Total Oportunidades</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalGeral }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-trophy text-green-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Ganhas</p>
                <p class="text-2xl font-bold text-green-600">{{ $ganhasGeral }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-times-circle text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Perdidas</p>
                <p class="text-2xl font-bold text-red-600">{{ $perdidasGeral }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-emerald-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase">Valor Total Ganho</p>
                <p class="text-2xl font-bold text-emerald-600">R$ {{ number_format($valorGeral, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Consultant cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($consultores as $consultor)
    <div class="bg-white rounded-xl border overflow-hidden hover:shadow-md transition">
        {{-- Header with consultant info --}}
        <div class="p-4 border-b bg-gradient-to-r from-primary-50 to-blue-50">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                    {{ strtoupper(substr($consultor->nome, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $consultor->nome }}</h3>
                    <p class="text-xs text-gray-500">{{ $consultor->email }}</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="p-4">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="text-center p-2 bg-gray-50 rounded-lg">
                    <p class="text-lg font-bold text-gray-800">{{ $consultor->stats->total }}</p>
                    <p class="text-xs text-gray-500">Total</p>
                </div>
                <div class="text-center p-2 bg-green-50 rounded-lg">
                    <p class="text-lg font-bold text-green-600">{{ $consultor->stats->ganhas }}</p>
                    <p class="text-xs text-gray-500">Ganhas</p>
                </div>
                <div class="text-center p-2 bg-red-50 rounded-lg">
                    <p class="text-lg font-bold text-red-600">{{ $consultor->stats->perdidas }}</p>
                    <p class="text-xs text-gray-500">Perdidas</p>
                </div>
                <div class="text-center p-2 bg-blue-50 rounded-lg">
                    <p class="text-lg font-bold text-blue-600">{{ $consultor->stats->abertas }}</p>
                    <p class="text-xs text-gray-500">Abertas</p>
                </div>
            </div>

            {{-- Conversion rate bar --}}
            <div class="mb-3">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-600">Taxa de Conversao</span>
                    <span class="text-xs font-bold {{ $consultor->stats->taxa_conversao >= 50 ? 'text-green-600' : ($consultor->stats->taxa_conversao >= 25 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $consultor->stats->taxa_conversao }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all {{ $consultor->stats->taxa_conversao >= 50 ? 'bg-green-500' : ($consultor->stats->taxa_conversao >= 25 ? 'bg-yellow-500' : 'bg-red-500') }}"
                         style="width: {{ min($consultor->stats->taxa_conversao, 100) }}%"></div>
                </div>
            </div>

            {{-- Total value --}}
            <div class="flex items-center justify-between pt-3 border-t">
                <span class="text-sm text-gray-600"><i class="fa-solid fa-dollar-sign mr-1"></i> Valor Total Ganho</span>
                <span class="text-sm font-bold text-green-600">R$ {{ number_format($consultor->stats->valor_total_ganho, 2, ',', '.') }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-xl border p-8 text-center text-gray-400">
        <i class="fa-solid fa-chart-bar text-4xl mb-3"></i>
        <p class="text-sm">Nenhum consultor com oportunidades encontrado.</p>
        <p class="text-xs mt-1">Atribua consultores as oportunidades para visualizar o desempenho.</p>
    </div>
    @endforelse
</div>
@endsection
