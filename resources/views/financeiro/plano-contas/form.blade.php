@extends('layouts.app')
@section('title', 'Cadastro do Plano de Contas')

@section('content')
<div class="w-full"
     x-data="{ tesouraria: {{ old('tesouraria', $conta->tesouraria ?? false) ? 'true' : 'false' }} }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">50</span>
            <h1 class="text-lg font-bold text-gray-800">Cadastro do Plano de Contas</h1>
        </div>
        <form method="POST" action="{{ isset($conta) ? route('financeiro.plano-contas.update', $conta) : route('financeiro.plano-contas.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($conta)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $conta->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo da Conta <span class="text-red-500">*</span></label>
                <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                    <option value="sintetica" {{ old('tipo', $conta->tipo ?? '') == 'sintetica' ? 'selected' : '' }}>Sintética (Agrupadora)</option>
                    <option value="analitica" {{ old('tipo', $conta->tipo ?? '') == 'analitica' ? 'selected' : '' }}>Analítica (Lançamento)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Pai</label>
                <select name="pai_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Nenhuma (Conta Raiz)</option>
                    @foreach($pais as $pai)
                    <option value="{{ $pai->id }}" {{ old('pai_id', $conta->pai_id ?? '') == $pai->id ? 'selected' : '' }}>{{ $pai->codigo }} - {{ $pai->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Lançamento</label>
                <select name="natureza" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="receita" {{ old('natureza', $conta->natureza ?? '') == 'receita' ? 'selected' : '' }}>Receita</option>
                    <option value="despesa" {{ old('natureza', $conta->natureza ?? '') == 'despesa' ? 'selected' : '' }}>Despesa</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Máscara dos Filhos Diretos</label>
                <input type="text" name="mascara_filhos" value="{{ old('mascara_filhos', $conta->mascara_filhos ?? '') }}" placeholder="Ex.: 9.9.99" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Superior</label>
                <input type="text" value="{{ isset($conta) && $conta->pai ? $conta->pai->codigo . ' - ' . $conta->pai->nome : '' }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50 text-gray-500" readonly>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="tesouraria" :value="tesouraria ? 1 : 0">
                <button type="button" @click="tesouraria = !tesouraria" :class="tesouraria ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="tesouraria ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">É Tesouraria?</span>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conta <span class="text-red-500">*</span></label>
                <input type="text" name="codigo" value="{{ old('codigo', $conta->codigo ?? '') }}" placeholder="Ex.: 1.1.01" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Identificador para integrações</label>
                <input type="text" name="identificador_integracao" value="{{ old('identificador_integracao', $conta->identificador_integracao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <input type="hidden" name="ativo" value="1">

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
