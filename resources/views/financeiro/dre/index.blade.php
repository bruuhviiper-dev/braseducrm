@extends('layouts.app')
@section('title', 'DRE — Demonstrativo de Resultados')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">111</span>
            <h1 class="text-lg font-semibold text-gray-800">DRE — Demonstrativo de Resultados</h1>
        </div>
        <form method="GET" action="{{ route('financeiro.dre.index') }}" class="p-4 flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Início</label>
                <input type="date" name="inicio" value="{{ $inicio }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Fim</label>
                <input type="date" name="fim" value="{{ $fim }}" class="border rounded-lg px-3 py-2 text-sm">
            </div>
            <button class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-filter mr-1"></i> Gerar</button>
        </form>
    </div>

    <div class="bg-white rounded-xl border p-6">
        <table class="w-full text-sm">
            <tbody class="divide-y">
                <tr class="bg-green-50/50">
                    <td class="px-4 py-3 font-semibold text-green-700" colspan="2">(+) RECEITAS</td>
                </tr>
                <tr><td class="px-8 py-2 text-gray-600">Títulos recebidos</td><td class="px-4 py-2 text-right text-gray-800">R$ {{ number_format($dre['receitasTitulos'], 2, ',', '.') }}</td></tr>
                <tr><td class="px-8 py-2 text-gray-600">Outras entradas (lançamentos)</td><td class="px-4 py-2 text-right text-gray-800">R$ {{ number_format($dre['receitasLancamentos'], 2, ',', '.') }}</td></tr>
                <tr class="font-semibold"><td class="px-4 py-2 text-gray-700">Total de Receitas</td><td class="px-4 py-2 text-right text-green-600">R$ {{ number_format($dre['totalReceitas'], 2, ',', '.') }}</td></tr>

                <tr class="bg-red-50/50">
                    <td class="px-4 py-3 font-semibold text-red-700" colspan="2">(−) DESPESAS</td>
                </tr>
                <tr><td class="px-8 py-2 text-gray-600">Títulos pagos</td><td class="px-4 py-2 text-right text-gray-800">R$ {{ number_format($dre['despesasTitulos'], 2, ',', '.') }}</td></tr>
                <tr><td class="px-8 py-2 text-gray-600">Outras saídas (lançamentos)</td><td class="px-4 py-2 text-right text-gray-800">R$ {{ number_format($dre['despesasLancamentos'], 2, ',', '.') }}</td></tr>
                <tr class="font-semibold"><td class="px-4 py-2 text-gray-700">Total de Despesas</td><td class="px-4 py-2 text-right text-red-600">R$ {{ number_format($dre['totalDespesas'], 2, ',', '.') }}</td></tr>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-gray-300">
                    <td class="px-4 py-4 text-base font-bold text-gray-800">(=) RESULTADO LÍQUIDO</td>
                    <td class="px-4 py-4 text-right text-base font-bold {{ $dre['resultado'] >= 0 ? 'text-green-600' : 'text-red-600' }}">R$ {{ number_format($dre['resultado'], 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
