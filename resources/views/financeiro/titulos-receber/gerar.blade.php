@extends('layouts.app')
@section('title', 'Manutenção de Títulos a Receber')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ gerarPara: '{{ $gerarPara ?? 'matricula' }}' }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('financeiro.titulos-receber.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">64</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Manutenção de Títulos a Receber</h1>
                <p class="text-xs text-gray-400">Financeiro › Títulos a receber</p>
            </div>
        </div>

        @if($errors->any())
        <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- Passo 1: Gerar para -> Carregar dados --}}
        <form method="POST" action="{{ route('financeiro.titulos-receber.carregar') }}" class="p-6 space-y-4 border-b">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gerar para <span class="text-red-500">*</span></label>
                    <select name="gerar_para" x-model="gerarPara" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="matricula">Matrícula</option>
                        <option value="pessoa">Pessoa</option>
                    </select>
                </div>
                <div x-show="gerarPara==='matricula'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matrícula <span class="text-red-500">*</span></label>
                    <select name="matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm" x-bind:required="gerarPara==='matricula'">
                        <option value="">Selecione...</option>
                        @foreach($matriculas as $m)<option value="{{ $m->id }}" @selected(($matriculaId ?? null)==$m->id)>{{ $m->numero_matricula ?? ('#'.$m->id) }} — {{ $m->aluno?->pessoa?->nome ?? 'Aluno '.$m->aluno_id }}{{ $m->turma ? ' ('.$m->turma->nome.')' : '' }}</option>@endforeach
                    </select>
                </div>
                <div x-show="gerarPara==='pessoa'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm" x-bind:required="gerarPara==='pessoa'">
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected(($pessoaId ?? null)==$p->id)>{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-3 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700"><i class="fa-solid fa-download mr-1"></i> Carregar dados</button>
        </form>

        {{-- Passo 2: Parcelas geradas -> Gerar títulos --}}
        @isset($parcelas)
        <form method="POST" action="{{ route('financeiro.titulos-receber.gerar') }}" class="p-6 space-y-4"
              x-data="{ parcelas: {{ \Illuminate\Support\Js::from($parcelas) }} }">
            @csrf
            <input type="hidden" name="pessoa_id" value="{{ $pessoaId }}">
            <input type="hidden" name="matricula_id" value="{{ $matriculaId }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_receber_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta Bancária</label>
                    <select name="conta_bancaria_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($contas as $conta)<option value="{{ $conta->id }}">{{ $conta->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                    <select name="forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach(['Boleto','PIX','Cartão de Crédito','Cartão de Débito','Dinheiro','Transferência','Cheque'] as $f)<option value="{{ $f }}">{{ $f }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="border rounded-lg overflow-hidden">
                <div class="px-4 py-2 bg-gray-50 border-b flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-700">Parcelas a gerar</p>
                    <button type="button" @click="parcelas.push({descricao:'Parcela', valor:0, vencimento:''})" class="text-xs text-primary-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Parcela</button>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase w-40">Valor (R$)</th>
                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase w-44">Vencimento</th>
                            <th class="px-3 py-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <template x-for="(p,i) in parcelas" :key="i">
                            <tr>
                                <td class="px-3 py-2"><input type="text" :name="`parcelas[${i}][descricao]`" x-model="p.descricao" class="w-full border rounded px-2 py-1.5 text-sm"></td>
                                <td class="px-3 py-2"><input type="number" step="0.01" min="0.01" :name="`parcelas[${i}][valor]`" x-model="p.valor" class="w-full border rounded px-2 py-1.5 text-sm" required></td>
                                <td class="px-3 py-2"><input type="date" :name="`parcelas[${i}][vencimento]`" x-model="p.vencimento" class="w-full border rounded px-2 py-1.5 text-sm" required></td>
                                <td class="px-3 py-2 text-center"><button type="button" @click="parcelas.splice(i,1)" class="text-red-600 hover:bg-red-50 rounded p-1"><i class="fa-solid fa-trash text-xs"></i></button></td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-gray-50 border-t">
                        <tr>
                            <td class="px-3 py-2 text-sm font-medium text-gray-600 text-right">Total:</td>
                            <td class="px-3 py-2 text-sm font-semibold text-gray-800" x-text="'R$ ' + parcelas.reduce((s,p)=>s+Number(p.valor||0),0).toLocaleString('pt-BR',{minimumFractionDigits:2})"></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('financeiro.titulos-receber.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Gerar Títulos</button>
            </div>
        </form>
        @else
        <div class="p-10 text-center text-gray-400">
            <i class="fa-solid fa-file-invoice-dollar text-3xl mb-2"></i>
            <p class="text-sm">Selecione o vínculo e clique em "Carregar dados" para gerar as parcelas.</p>
        </div>
        @endisset
    </div>
</div>
@endsection
