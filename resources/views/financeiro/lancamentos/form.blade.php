@extends('layouts.app')
@section('title', isset($lancamento) ? 'Editar Lançamento' : 'Novo Lançamento')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($lancamento) ? 'Editar Lançamento' : 'Novo Lançamento Financeiro' }}</h2>
            <a href="{{ route('financeiro.lancamentos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($lancamento) ? route('financeiro.lancamentos.update', $lancamento) : route('financeiro.lancamentos.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($lancamento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="descricao" value="{{ old('descricao', $lancamento->descricao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="entrada" {{ old('tipo', $lancamento->tipo ?? 'entrada') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                        <option value="saida" {{ old('tipo', $lancamento->tipo ?? '') == 'saida' ? 'selected' : '' }}>Saída</option>
                        <option value="transferencia" {{ old('tipo', $lancamento->tipo ?? '') == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $lancamento->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data <span class="text-red-500">*</span></label>
                    <input type="date" name="data_lancamento" value="{{ old('data_lancamento', isset($lancamento) ? $lancamento->data_lancamento->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta Bancária <span class="text-red-500">*</span></label>
                    <select name="conta_bancaria_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($contas as $c)
                        <option value="{{ $c->id }}" {{ old('conta_bancaria_id', $lancamento->conta_bancaria_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plano de Contas</label>
                    <select name="plano_conta_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($planos as $p)
                        <option value="{{ $p->id }}" {{ old('plano_conta_id', $lancamento->plano_conta_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->codigo }} - {{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Documento de Referência</label>
                <input type="text" name="documento_referencia" value="{{ old('documento_referencia', $lancamento->documento_referencia ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">{{ isset($lancamento) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('financeiro.lancamentos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
