@extends('layouts.app')
@section('title', 'Cadastro de Contas')

@section('content')
<div class="w-full"
     x-data="{
        aba: 'contas',
        tesouraria: {{ old('tesouraria', $conta->tesouraria ?? false) ? 'true' : 'false' }},
        recebCaixa: {{ old('recebimento_caixa', $conta->recebimento_caixa ?? false) ? 'true' : 'false' }},
        ehBancaria: {{ old('eh_conta_bancaria', $conta->eh_conta_bancaria ?? true) ? 'true' : 'false' }},
        ignorarPlanos: {{ old('ignorar_novos_planos', $conta->ignorar_novos_planos ?? false) ? 'true' : 'false' }},
        ocultarSaldo: {{ old('ocultar_saldo_painel', $conta->ocultar_saldo_painel ?? false) ? 'true' : 'false' }},
        descRelatorios: {{ old('desconsiderar_relatorios', $conta->desconsiderar_relatorios ?? false) ? 'true' : 'false' }}
     }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">63</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Contas</h1>
                <p class="text-xs text-primary-500">Financeiro › Cadastros Essenciais</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b flex gap-5">
            <button type="button" @click="aba = 'contas'" :class="aba === 'contas' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Contas</button>
            <button type="button" @click="aba = 'gateway'" :class="aba === 'gateway' ? 'text-cyan-600 border-cyan-500' : 'text-gray-500 border-transparent'" class="pb-2 text-sm font-semibold border-b-2">Gateway</button>
        </div>
        <form method="POST" action="{{ isset($conta) ? route('financeiro.contas-bancarias.update', $conta) : route('financeiro.contas-bancarias.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($conta)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div x-show="aba === 'contas'" class="space-y-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="tesouraria" :value="tesouraria ? 1 : 0">
                    <button type="button" @click="tesouraria = !tesouraria" :class="tesouraria ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="tesouraria ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Conta Tesouraria</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="recebimento_caixa" :value="recebCaixa ? 1 : 0">
                    <button type="button" @click="recebCaixa = !recebCaixa" :class="recebCaixa ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="recebCaixa ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Conta para recebimentos em caixa</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="eh_conta_bancaria" :value="ehBancaria ? 1 : 0">
                    <button type="button" @click="ehBancaria = !ehBancaria" :class="ehBancaria ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="ehBancaria ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">É conta bancária?</span>
                </label>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $conta->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instituição de Ensino</label>
                    <select name="instituicao_ensino_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach(\App\Models\InstituicaoEnsino::orderBy('nome')->get() as $ie)
                        <option value="{{ $ie->id }}" {{ old('instituicao_ensino_id', $conta->instituicao_ensino_id ?? '') == $ie->id ? 'selected' : '' }}>{{ $ie->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="ehBancaria" x-cloak class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                        <input type="text" name="banco" value="{{ old('banco', $conta->banco ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agência</label>
                        <input type="text" name="agencia" value="{{ old('agencia', $conta->agencia ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Conta</label>
                        <input type="text" name="conta" value="{{ old('conta', $conta->conta ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                </div>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="ignorar_novos_planos" :value="ignorarPlanos ? 1 : 0">
                    <button type="button" @click="ignorarPlanos = !ignorarPlanos" :class="ignorarPlanos ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="ignorarPlanos ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Deseja ignorar a conta no cadastro de novos planos de conta?</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="ocultar_saldo_painel" :value="ocultarSaldo ? 1 : 0">
                    <button type="button" @click="ocultarSaldo = !ocultarSaldo" :class="ocultarSaldo ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="ocultarSaldo ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Ocultar saldo em 138 - Painel Financeiro Geral.</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="desconsiderar_relatorios" :value="descRelatorios ? 1 : 0">
                    <button type="button" @click="descRelatorios = !descRelatorios" :class="descRelatorios ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="descRelatorios ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Desconsiderar lançamentos e faturas associadas a esta conta nos relatórios dos painéis?</span>
                </label>

                <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3 text-xs text-blue-700">
                    <strong>Aviso</strong><br>Informe a descrição resumida da conta, para facilitar na identificação da conta no plano de contas.
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição Resumida</label>
                    <input type="text" name="descricao_resumida" value="{{ old('descricao_resumida', $conta->descricao_resumida ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Saldo inicial</label>
                        <input type="number" step="0.01" name="saldo_inicial" value="{{ old('saldo_inicial', $conta->saldo_inicial ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                        <input type="date" name="data_saldo" value="{{ old('data_saldo', isset($conta) && $conta->data_saldo ? \Illuminate\Support\Carbon::parse($conta->data_saldo)->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>
                </div>

                <input type="hidden" name="ativo" value="1">
            </div>

            <div x-show="aba === 'gateway'" x-cloak>
                <p class="text-sm text-gray-500">Nenhum gateway de pagamento configurado para esta conta. As integrações são definidas em <strong>167 - Integrações</strong>.</p>
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
