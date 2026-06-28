@extends('layouts.app')
@section('title', 'Painel Comercial')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">142</span>
        <h1 class="text-lg font-semibold text-gray-800">Painel Comercial Geral</h1>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-gray-800">{{ $stats['interessados'] }}</div><div class="text-xs text-gray-500 mt-1">Interessados</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-gray-800">{{ $stats['oportunidades'] }}</div><div class="text-xs text-gray-500 mt-1">Oportunidades</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-green-600">{{ $stats['ganhas'] }}</div><div class="text-xs text-gray-500 mt-1">Ganhas</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-lg font-bold text-green-600">R$ {{ number_format($stats['valor_ganho'], 0, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Valor ganho</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-lg font-bold text-amber-600">R$ {{ number_format($stats['valor_aberto'], 0, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Em aberto</div></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Oportunidades por etapa (funil)</h2>
            <canvas id="chartEtapas" height="200"></canvas>
        </div>
        <div class="bg-white rounded-xl border p-5">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Oportunidades por situação</h2>
            <canvas id="chartSituacao" height="200"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
    new Chart(document.getElementById('chartEtapas'), {
        type: 'bar',
        data: {
            labels: @json(array_keys($porEtapa)),
            datasets: [{ label: 'Abertas', data: @json(array_values($porEtapa)), backgroundColor: '#3b82f6' }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    new Chart(document.getElementById('chartSituacao'), {
        type: 'doughnut',
        data: {
            labels: @json($porSituacao->keys()),
            datasets: [{ data: @json($porSituacao->values()), backgroundColor: ['#3b82f6','#22c55e','#ef4444','#f59e0b'] }]
        }
    });
</script>
@endpush
@endsection
