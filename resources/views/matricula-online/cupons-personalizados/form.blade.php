@extends('layouts.app')
@section('title', $cupom ? 'Editar Cupom' : 'Novo Cupom Personalizado')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">193</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $cupom ? 'Editar Cupom' : 'Novo Cupom Personalizado' }}</h1>
        </div>
        <form action="{{ $cupom ? route('matricula-online.cupons-personalizados.update', $cupom) : route('matricula-online.cupons-personalizados.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($cupom) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código <span class="text-red-500">*</span></label>
                    <input type="text" name="codigo" value="{{ old('codigo', $cupom->codigo ?? $sugestao) }}" required class="w-full border rounded-lg px-3 py-2 text-sm font-mono uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Beneficiário</label>
                    <input type="text" name="beneficiario" value="{{ old('beneficiario', $cupom->beneficiario ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de desconto <span class="text-red-500">*</span></label>
                    <select name="tipo_desconto" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\CupomPersonalizado::TIPOS as $k => $v)<option value="{{ $k }}" @selected(old('tipo_desconto', $cupom->tipo_desconto ?? 'percentual')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor do desconto <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="valor_desconto" value="{{ old('valor_desconto', $cupom->valor_desconto ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Validade</label>
                    <input type="date" name="validade" value="{{ old('validade', optional($cupom?->validade)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="ativo" value="1" {{ old('ativo', $cupom->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativo</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="usado" value="1" {{ old('usado', $cupom->usado ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Já utilizado</label>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('matricula-online.cupons-personalizados.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
