@extends('layouts.app')
@section('title', 'Painel Financeiro')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">138</span>
        <h1 class="text-lg font-semibold text-gray-800">Painel Financeiro Geral</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-amber-600">R$ {{ number_format($stats['total_aberto'], 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">A receber (aberto)</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-green-600">R$ {{ number_format($stats['total_pago'], 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Recebido</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-xl font-bold text-red-600">R$ {{ number_format($stats['total_vencido'], 2, ',', '.') }}</div><div class="text-xs text-gray-500 mt-1">Vencido</div></div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">A Receber vs Recebido (últimos 6 meses)</h2>
        <canvas id="chartFin" height="100"></canvas>
    </div>
</div>

@push('scripts')
<script>
    new Chart(document.getElementById('chartFin'), {
        type: 'bar',
        data: {
            labels: @json($meses),
            datasets: [
                { label: 'A Receber', data: @json($receber), backgroundColor: '#f59e0b' },
                { label: 'Recebido', data: @json($pago), backgroundColor: '#22c55e' }
            ]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endpush
@endsection
