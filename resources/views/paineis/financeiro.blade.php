@extends('layouts.app')
@section('title', 'Painel Financeiro Geral')

@php
    $brl = fn($v) => 'R$ ' . number_format($v, 2, ',', '.');
@endphp

@section('content')
<div class="space-y-4">
    <div class="flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">138</span>
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Painel Financeiro Geral</h1>
            <p class="text-xs text-gray-400">Dashboard › Painéis</p>
        </div>
    </div>

    {{-- KPIs (fiel ao EDUQ) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-money-bill-trend-up text-orange-400"></i> Resultado previsto</p>
            <p class="text-2xl font-bold {{ $kpis['resultado_previsto'] >= 0 ? 'text-orange-500' : 'text-red-500' }} mt-2">{{ $brl($kpis['resultado_previsto']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Resultado previsto (Receita - Despesa)</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-hand-holding-dollar text-blue-400"></i> Resultado realizado</p>
            <p class="text-2xl font-bold text-blue-500 mt-2">{{ $brl($kpis['resultado_realizado']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Taxa realizada: {{ number_format($kpis['taxa_realizada'],0) }}%</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-chart-line text-green-400"></i> Saldo acumulado</p>
            <p class="text-2xl font-bold {{ $kpis['saldo_acumulado'] >= 0 ? 'text-green-500' : 'text-red-500' }} mt-2">{{ $brl($kpis['saldo_acumulado']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Saldo acumulado até a data atual</p>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm text-gray-500 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation text-purple-400"></i> Taxa de inadimplência</p>
            <p class="text-2xl font-bold text-purple-500 mt-2">{{ number_format($kpis['inadimplencia'],2) }}%</p>
            <p class="text-xs text-gray-400 mt-1">Vencidas sobre o total a receber</p>
        </div>
    </div>

    {{-- A Receber / A Pagar --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- A RECEBER --}}
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm font-medium text-gray-600 flex items-center gap-2 mb-3"><i class="fa-solid fa-arrow-down text-green-500"></i> A receber</p>
            <p class="text-2xl font-bold text-gray-800 text-center">{{ $brl($aReceber['total']) }}</p>
            <p class="text-xs text-gray-400 text-center mb-4">(Total)</p>
            <div class="space-y-2">
                @foreach([['Recebidas',$aReceber['recebidas'],'bg-blue-500'],['Vencidas',$aReceber['vencidas'],'bg-red-500'],['A vencer',$aReceber['a_vencer'],'bg-orange-500']] as [$lbl,$d,$cor])
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ $lbl }}</span>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">{{ $brl($d['valor']) }}</span>
                        <span class="text-white text-xs px-2 py-0.5 rounded {{ $cor }}">{{ number_format($d['pct'],2) }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 h-2.5 rounded-full overflow-hidden bg-gray-100 flex">
                <div class="bg-blue-500 h-full" style="width: {{ $aReceber['recebidas']['pct'] }}%"></div>
                <div class="bg-red-500 h-full" style="width: {{ $aReceber['vencidas']['pct'] }}%"></div>
                <div class="bg-orange-500 h-full" style="width: {{ $aReceber['a_vencer']['pct'] }}%"></div>
            </div>
        </div>

        {{-- A PAGAR --}}
        <div class="bg-white rounded-xl border p-5">
            <p class="text-sm font-medium text-gray-600 flex items-center gap-2 mb-3"><i class="fa-solid fa-arrow-up text-red-500"></i> A pagar</p>
            <p class="text-2xl font-bold text-gray-800 text-center">{{ $brl($aPagar['total']) }}</p>
            <p class="text-xs text-gray-400 text-center mb-4">(Total)</p>
            <div class="space-y-2">
                @foreach([['Pagas',$aPagar['pagas'],'bg-blue-500'],['Vencidas',$aPagar['vencidas'],'bg-red-500'],['A vencer',$aPagar['a_vencer'],'bg-orange-500']] as [$lbl,$d,$cor])
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">{{ $lbl }}</span>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">{{ $brl($d['valor']) }}</span>
                        <span class="text-white text-xs px-2 py-0.5 rounded {{ $cor }}">{{ number_format($d['pct'],2) }}%</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 h-2.5 rounded-full overflow-hidden bg-gray-100 flex">
                <div class="bg-blue-500 h-full" style="width: {{ $aPagar['pagas']['pct'] }}%"></div>
                <div class="bg-red-500 h-full" style="width: {{ $aPagar['vencidas']['pct'] }}%"></div>
                <div class="bg-orange-500 h-full" style="width: {{ $aPagar['a_vencer']['pct'] }}%"></div>
            </div>
        </div>
    </div>

    {{-- Evolução receitas x despesas --}}
    <div class="bg-white rounded-xl border p-5">
        <p class="text-sm font-medium text-gray-600 mb-3">Evolução das receitas x despesas</p>
        <canvas id="chartRecDesp" height="90"></canvas>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('chartRecDesp'), {
    type: 'bar',
    data: {
        labels: @json($meses),
        datasets: [
            { label: 'Receitas', data: @json($receitas), backgroundColor: '#22c55e' },
            { label: 'Despesas', data: @json($despesas), backgroundColor: '#ef4444' },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { ticks: { callback: v => 'R$ ' + v.toLocaleString('pt-BR') } } }
    }
});
</script>
@endpush
@endsection
