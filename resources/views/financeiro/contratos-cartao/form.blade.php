@extends('layouts.app')
@section('title', $contrato ? 'Editar Contrato de Cartão' : 'Novo Contrato de Cartão')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">70</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $contrato ? 'Editar Contrato de Cartão' : 'Novo Contrato de Cartão' }}</h1>
        </div>
        <form action="{{ $contrato ? route('financeiro.contratos-cartao.update', $contrato) : route('financeiro.contratos-cartao.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($contrato) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operadora <span class="text-red-500">*</span></label>
                    <input type="text" name="operadora" value="{{ old('operadora', $contrato->operadora ?? '') }}" required placeholder="Cielo, Rede, Stone..." class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <input type="text" name="descricao" value="{{ old('descricao', $contrato->descricao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta bancária de crédito</label>
                    <select name="conta_bancaria_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($contas as $c)<option value="{{ $c->id }}" @selected(old('conta_bancaria_id', $contrato->conta_bancaria_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taxa Débito (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="taxa_debito" value="{{ old('taxa_debito', $contrato->taxa_debito ?? '0') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taxa Créd. à vista (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="taxa_credito_vista" value="{{ old('taxa_credito_vista', $contrato->taxa_credito_vista ?? '0') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Taxa Créd. parcelado (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="taxa_credito_parcelado" value="{{ old('taxa_credito_parcelado', $contrato->taxa_credito_parcelado ?? '0') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prazo de recebimento (dias)</label>
                    <input type="number" min="0" name="prazo_recebimento_dias" value="{{ old('prazo_recebimento_dias', $contrato->prazo_recebimento_dias ?? '30') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $contrato->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativo
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('financeiro.contratos-cartao.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
