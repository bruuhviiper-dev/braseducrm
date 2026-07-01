@extends('layouts.app')
@section('title', $cartao ? 'Editar Cartão Empresarial' : 'Novo Cartão Empresarial')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">136</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $cartao ? 'Editar Cartão Empresarial' : 'Novo Cartão Empresarial' }}</h1>
        </div>
        <form action="{{ $cartao ? route('financeiro.cartoes-empresariais.update', $cartao) : route('financeiro.cartoes-empresariais.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($cartao) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome / Apelido <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $cartao->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bandeira</label>
                    <input type="text" name="bandeira" value="{{ old('bandeira', $cartao->bandeira ?? '') }}" placeholder="Visa, Mastercard..." class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Últimos 4 dígitos</label>
                    <input type="text" maxlength="4" name="ultimos_digitos" value="{{ old('ultimos_digitos', $cartao->ultimos_digitos ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banco emissor</label>
                    <select name="banco_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($bancos as $b)<option value="{{ $b->id }}" @selected(old('banco_id', $cartao->banco_id ?? '')==$b->id)>{{ $b->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Limite (R$)</label>
                    <input type="number" step="0.01" min="0" name="limite" value="{{ old('limite', $cartao->limite ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dia fechamento</label>
                        <input type="number" min="1" max="31" name="dia_fechamento" value="{{ old('dia_fechamento', $cartao->dia_fechamento ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dia vencimento</label>
                        <input type="number" min="1" max="31" name="dia_vencimento" value="{{ old('dia_vencimento', $cartao->dia_vencimento ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $cartao->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativo
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('financeiro.cartoes-empresariais.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
