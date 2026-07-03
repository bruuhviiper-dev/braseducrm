@extends('layouts.app')
@section('title', 'Cadastro de Desconto Incondicional')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">57</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Desconto Incondicional</h1>
                <p class="text-xs text-primary-500">Financeiro › Cadastros Essenciais</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados Básicos</span>
        </div>
        <form method="POST" action="{{ isset($desconto) ? route('financeiro.descontos.update', $desconto) : route('financeiro.descontos.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($desconto)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $desconto->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                    <option value="percentual" {{ old('tipo', $desconto->tipo ?? 'percentual') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                    <option value="valor" {{ old('tipo', $desconto->tipo ?? '') == 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $desconto->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <input type="hidden" name="ativo" value="1">

            <div class="flex justify-end pt-3 border-t">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
