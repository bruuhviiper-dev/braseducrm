@extends('layouts.app')
@section('title', 'Painel Acadêmico')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">144</span>
        <h1 class="text-lg font-semibold text-gray-800">Painel Acadêmico Geral</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-gray-800">{{ $stats['matriculas'] }}</div><div class="text-xs text-gray-500 mt-1">Matrículas totais</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-green-600">{{ $stats['ativas'] }}</div><div class="text-xs text-gray-500 mt-1">Ativas</div></div>
        <div class="bg-white rounded-xl border p-4"><div class="text-2xl font-bold text-blue-600">{{ $stats['concluidas'] }}</div><div class="text-xs text-gray-500 mt-1">Concluídas</div></div>
    </div>

    <div class="bg-white rounded-xl border p-5 max-w-md">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Matrículas por situação</h2>
        <canvas id="chartMat" height="220"></canvas>
    </div>
</div>

@push('scripts')
<script>
    new Chart(document.getElementById('chartMat'), {
        type: 'pie',
        data: {
            labels: @json($porSituacao->keys()),
            datasets: [{ data: @json($porSituacao->values()), backgroundColor: ['#22c55e','#f59e0b','#ef4444','#3b82f6','#a855f7','#64748b'] }]
        }
    });
</script>
@endpush
@endsection
