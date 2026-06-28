@extends('layouts.app')
@section('title', 'Configuração do Financeiro')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center gap-3 px-6 py-4 border-b">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">59</span>
            <h2 class="text-base font-semibold text-gray-800">Configuração do Financeiro</h2>
        </div>
        <form method="POST" action="{{ route('financeiro.configuracao.index') }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Multa por atraso (%)</label>
                    <input type="number" step="0.01" min="0" name="multa_atraso" value="{{ old('multa_atraso', $config->multa_atraso) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Juros ao dia (%)</label>
                    <input type="number" step="0.0001" min="0" name="juros_dia" value="{{ old('juros_dia', $config->juros_dia) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <label class="flex items-start gap-3">
                <input type="checkbox" name="boleto_automatico" value="1" {{ old('boleto_automatico', $config->boleto_automatico) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Boleto automático</span><span class="block text-xs text-gray-400">Gera boletos automaticamente para os títulos a receber.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="cartao_recorrente" value="1" {{ old('cartao_recorrente', $config->cartao_recorrente) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600">
                <span><span class="block text-sm font-medium text-gray-700">Cartão recorrente</span><span class="block text-xs text-gray-400">Cobrança recorrente no cartão de crédito.</span></span>
            </label>

            <div class="border-t pt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Salvar Configuração</button>
            </div>
        </form>
    </div>
</div>
@endsection
