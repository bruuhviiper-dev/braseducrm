@extends('layouts.app')
@section('title', 'Lançar Recebimento de Cartão')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">71</span>
            <h1 class="text-lg font-semibold text-gray-800">Lançar Recebimento de Cartão</h1>
        </div>
        @if($contratos->isEmpty())
        <div class="p-6">
            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded text-sm">
                Cadastre primeiro um <a href="{{ route('financeiro.contratos-cartao.create') }}" class="underline font-medium">Contrato de Cartão (70)</a> com as taxas da operadora.
            </div>
        </div>
        @else
        <form action="{{ route('financeiro.conciliacao-cartao.store') }}" method="POST" class="p-6 space-y-4"
              x-data="{ modalidade: '{{ old('modalidade', 'credito_vista') }}' }">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contrato / Operadora <span class="text-red-500">*</span></label>
                <select name="contrato_cartao_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($contratos as $c)
                    <option value="{{ $c->id }}" @selected(old('contrato_cartao_id')==$c->id)>{{ $c->operadora }} (déb {{ number_format($c->taxa_debito,2,',','.') }}% / vista {{ number_format($c->taxa_credito_vista,2,',','.') }}% / parc {{ number_format($c->taxa_credito_parcelado,2,',','.') }}%)</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">A taxa e o valor líquido são calculados automaticamente pela modalidade.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da venda <span class="text-red-500">*</span></label>
                    <input type="date" name="data_venda" value="{{ old('data_venda', date('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bandeira</label>
                    <input type="text" name="bandeira" value="{{ old('bandeira') }}" placeholder="Visa, Master..." class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modalidade <span class="text-red-500">*</span></label>
                    <select name="modalidade" x-model="modalidade" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\RecebimentoCartao::MODALIDADES as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div x-show="modalidade === 'credito_parcelado'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parcelas</label>
                    <input type="number" min="1" max="36" name="parcelas" value="{{ old('parcelas', 2) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor bruto (R$) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="valor_bruto" value="{{ old('valor_bruto') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('financeiro.conciliacao-cartao.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Lançar</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection
