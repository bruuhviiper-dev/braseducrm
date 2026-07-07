@extends('layouts.app')
@section('title', ($matricula ?? null) ? 'Editar Matrícula' : 'Efetuar Matrícula')

@section('content')
@php
    $docIni = ($matricula ?? null) ? $matricula->documentos->map(fn($d)=>['documento'=>$d->documento,'entregue'=>(bool)$d->entregue,'observacao'=>$d->observacao])->values() : [];
@endphp
<div class="w-full" x-data="matriculaForm(@js($docIni))">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.matriculas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">23</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($matricula ?? null) ? 'Editar Matrícula' : 'Efetuar Matrícula' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Matrícula</p>
            </div>
        </div>

        {{-- Abas (estilo EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['dados'=>'Dados da matrícula','pagamento'=>'Plano de pagamento','documentos'=>'Documentos'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($matricula ?? null) ? route('academico.matriculas.update', $matricula) : route('academico.matriculas.store') }}" method="POST" class="p-6">
            @csrf
            @if($matricula ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- DADOS DA MATRÍCULA --}}
            <div x-show="tab==='dados'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aluno <span class="text-red-500">*</span></label>
                        <select name="aluno_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($alunos as $a)<option value="{{ $a->id }}" @selected(old('aluno_id', $matricula->aluno_id ?? '')==$a->id)>{{ $a->pessoa->nome ?? 'Aluno #'.$a->id }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Turma <span class="text-red-500">*</span></label>
                        <select name="turma_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $matricula->turma_id ?? '')==$t->id)>{{ $t->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nº da Matrícula</label>
                        <input type="text" name="numero_matricula" value="{{ old('numero_matricula', $matricula->numero_matricula ?? '') }}" placeholder="Gerado automaticamente se vazio" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data da Matrícula <span class="text-red-500">*</span></label>
                        <input type="date" name="data_matricula" value="{{ old('data_matricula', optional($matricula->data_matricula ?? null)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                        <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            @foreach(['nao_confirmada'=>'Não Confirmada','confirmada'=>'Confirmada','ativa'=>'Ativa','trancada'=>'Trancada','desistente'=>'Desistente','cancelada'=>'Cancelada','dependencia'=>'Dependência (DP)','concluida'=>'Concluída','transferida'=>'Transferida','evadida'=>'Evadida'] as $val=>$lbl)
                            <option value="{{ $val }}" @selected(old('situacao', $matricula->situacao ?? 'nao_confirmada')===$val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Ingresso</label>
                        <select name="forma_ingresso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($formasIngresso as $fi)<option value="{{ $fi->id }}" @selected(old('forma_ingresso_id', $matricula->forma_ingresso_id ?? '')==$fi->id)>{{ $fi->nome }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="observacoes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $matricula->observacoes ?? '') }}</textarea>
                </div>
            </div>

            {{-- PLANO DE PAGAMENTO --}}
            <div x-show="tab==='pagamento'" x-cloak class="space-y-4">
                <p class="text-sm text-gray-500">Condições financeiras da matrícula. As parcelas podem ser calculadas automaticamente.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor Total (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_total" x-model.number="valorTotal" value="{{ old('valor_total', $matricula->valor_total ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desconto (R$)</label>
                        <input type="number" step="0.01" min="0" name="desconto" x-model.number="desconto" value="{{ old('desconto', $matricula->desconto ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                        <select name="forma_pagamento_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($formasPagamento as $fp)<option value="{{ $fp->id }}" @selected(old('forma_pagamento_id', $matricula->forma_pagamento_id ?? '')==$fp->id)>{{ $fp->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nº de Parcelas</label>
                        <input type="number" min="1" max="120" name="num_parcelas" x-model.number="numParcelas" value="{{ old('num_parcelas', $matricula->num_parcelas ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor da Parcela (R$)</label>
                        <input type="number" step="0.01" min="0" name="valor_parcela" x-model.number="valorParcela" value="{{ old('valor_parcela', $matricula->valor_parcela ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
                        <p class="text-xs text-primary-600 mt-1 cursor-pointer" @click="calcularParcela()"><i class="fa-solid fa-calculator mr-1"></i>Calcular ((total - desconto) / parcelas)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dia de Vencimento</label>
                        <input type="number" min="1" max="31" name="dia_vencimento" value="{{ old('dia_vencimento', $matricula->dia_vencimento ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">1º Vencimento</label>
                        <input type="date" name="primeiro_vencimento" value="{{ old('primeiro_vencimento', optional($matricula->primeiro_vencimento ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            {{-- DOCUMENTOS --}}
            <div x-show="tab==='documentos'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Checklist de documentos entregues pelo aluno.</p>
                    <button type="button" @click="addDoc()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Documento</button>
                </div>
                <template x-for="(d,i) in docs" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-6 gap-2 items-center">
                        <input type="text" :name="`documentos[${i}][documento]`" x-model="d.documento" placeholder="Documento (ex.: RG, CPF, Histórico)" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <input type="text" :name="`documentos[${i}][observacao]`" x-model="d.observacao" placeholder="Observação" class="border rounded px-2 py-1.5 text-sm md:col-span-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" :name="`documentos[${i}][entregue]`" value="1" x-model="d.entregue" class="rounded border-gray-300 text-primary-600"> Entregue
                        </label>
                        <button type="button" @click="docs.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded justify-self-end"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </template>
                <p x-show="docs.length===0" class="text-xs text-gray-400 py-2">Nenhum documento na lista.</p>
            </div>

            <div class="flex justify-end items-center gap-3 pt-4 mt-4 sticky bottom-4 z-10">
                <a href="{{ route('academico.matriculas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function matriculaForm(docIni) {
    return {
        tab: 'dados',
        valorTotal: {{ is_numeric(old('valor_total', $matricula->valor_total ?? null)) ? old('valor_total', $matricula->valor_total) : 'null' }},
        desconto: {{ is_numeric(old('desconto', $matricula->desconto ?? null)) ? old('desconto', $matricula->desconto) : 'null' }},
        numParcelas: {{ is_numeric(old('num_parcelas', $matricula->num_parcelas ?? null)) ? old('num_parcelas', $matricula->num_parcelas) : 'null' }},
        valorParcela: {{ is_numeric(old('valor_parcela', $matricula->valor_parcela ?? null)) ? old('valor_parcela', $matricula->valor_parcela) : 'null' }},
        docs: (docIni||[]).map(d=>({documento:d.documento??'',entregue:!!d.entregue,observacao:d.observacao??''})),
        addDoc() { this.docs.push({documento:'',entregue:false,observacao:''}); },
        calcularParcela() {
            const total = Number(this.valorTotal||0) - Number(this.desconto||0);
            const n = Number(this.numParcelas||0);
            if (n > 0 && total > 0) this.valorParcela = Math.round((total/n)*100)/100;
        },
    };
}
</script>
@endsection
