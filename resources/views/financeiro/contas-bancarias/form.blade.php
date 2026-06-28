@extends('layouts.app')
@section('title', isset($conta) ? 'Editar Conta' : 'Nova Conta')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($conta) ? 'Editar Conta Bancária' : 'Nova Conta Bancária' }}</h2>
            <a href="{{ route('financeiro.contas-bancarias.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($conta) ? route('financeiro.contas-bancarias.update', $conta) : route('financeiro.contas-bancarias.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($conta)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Conta <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $conta->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                    <input type="text" name="banco" value="{{ old('banco', $conta->banco ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Conta</label>
                    <select name="tipo_conta" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @php $tc = old('tipo_conta', $conta->tipo_conta ?? ''); @endphp
                        <option value="">Selecione...</option>
                        <option value="corrente" {{ $tc == 'corrente' ? 'selected' : '' }}>Conta Corrente</option>
                        <option value="poupanca" {{ $tc == 'poupanca' ? 'selected' : '' }}>Poupança</option>
                        <option value="caixa" {{ $tc == 'caixa' ? 'selected' : '' }}>Caixa Interno</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agência</label>
                    <input type="text" name="agencia" value="{{ old('agencia', $conta->agencia ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta</label>
                    <input type="text" name="conta" value="{{ old('conta', $conta->conta ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Inicial</label>
                    <input type="number" step="0.01" name="saldo_inicial" value="{{ old('saldo_inicial', $conta->saldo_inicial ?? '0') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $conta->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativa</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($conta) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('financeiro.contas-bancarias.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
