@extends('layouts.app')
@section('title', 'Cadastro de Categorias (A Receber)')

@section('content')
<div class="w-full"
     x-data="{ aba: 'dados', ativo: {{ old('ativo', $categoria->ativo ?? true) ? 'true' : 'false' }} }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">65</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Categorias (A Receber)</h1>
                <p class="text-xs text-primary-500">Financeiro › Cadastros Essenciais</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5">
            <button type="button" @click="aba = 'dados'" :class="aba === 'dados' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Dados Básicos</button>
            <button type="button" @click="aba = 'config'" :class="aba === 'config' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Configuração</button>
        </div>
        <form method="POST" action="{{ isset($categoria) ? route('financeiro.categorias-receber.update', $categoria) : route('financeiro.categorias-receber.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($categoria)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'dados'" class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="ativo" :value="ativo ? 1 : 0">
                    <button type="button" @click="ativo = !ativo" :class="ativo ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="ativo ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Ativo</span>
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $categoria->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>
            </div>

            <div x-show="aba === 'config'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta do Plano de Contas</label>
                    <select name="plano_conta_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach(\App\Models\PlanoContas::where('tipo', 'analitica')->orderBy('codigo')->get() as $pc)
                        <option value="{{ $pc->id }}" {{ old('plano_conta_id', $categoria->plano_conta_id ?? '') == $pc->id ? 'selected' : '' }}>{{ $pc->codigo }} - {{ $pc->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
