@extends('layouts.app')
@section('title', 'Fluxo de Caixa')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">78</span>
            <h1 class="text-lg font-semibold text-gray-800">Fluxo de Caixa</h1>
        </div>
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('financeiro.fluxo-caixa.index') }}" class="flex items-center gap-2">
                <select name="ano" onchange="this.form.submit()" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    @for($y = date('Y') + 1; $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $ano == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>

    <div class="p-5">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-green-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Total Receitas</p>
                <p class="text-xl font-bold text-green-600">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Total Despesas</p>
                <p class="text-xl font-bold text-red-600">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
            </div>
            <div class="{{ $saldoGeral >= 0 ? 'bg-blue-50' : 'bg-orange-50' }} rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Saldo Geral</p>
                <p class="text-xl font-bold {{ $saldoGeral >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                    R$ {{ number_format($saldoGeral, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Chart --}}
        <div class="mb-6">
            <div class="bg-gray-50 rounded-xl p-4">
                <canvas id="fluxoCaixaChart" height="100"></canvas>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Mes</th>
                        <th class="text-right py-3 px-4 font-semibold text-green-600">Receitas</th>
                        <th class="text-right py-3 px-4 font-semibold text-red-600">Despesas</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meses as $mes)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $mes['nome'] }}</td>
                        <td class="py-3 px-4 text-right text-green-600">R$ {{ number_format($mes['receitas'], 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right text-red-600">R$ {{ number_format($mes['despesas'], 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-semibold {{ $mes['saldo'] >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                            R$ {{ number_format($mes['saldo'], 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td class="py-3 px-4 text-gray-800">Total</td>
                        <td class="py-3 px-4 text-right text-green-600">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right text-red-600">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right {{ $saldoGeral >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                            R$ {{ number_format($saldoGeral, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('fluxoCaixaChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($meses, 'nome')) !!},
            datasets: [
                {
                    label: 'Receitas',
                    data: {!! json_encode(array_column($meses, 'receitas')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Despesas',
                    data: {!! json_encode(array_column($meses, 'despesas')) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': R$ ' +
                                context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 0 });
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
