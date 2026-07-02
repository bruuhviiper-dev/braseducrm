@extends('layouts.app')
@section('title', ($titulo ?? null) ? 'Editar Título a Pagar' : 'Manutenção de Títulos a Pagar')

@section('content')
@php
    $ratIni = ($titulo ?? null) ? $titulo->rateios->map(fn($r)=>['centro_custo_id'=>$r->centro_custo_id,'valor'=>$r->valor])->values() : [];
@endphp
<div class="max-w-5xl mx-auto" x-data="tituloPagarForm(@js($ratIni))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('financeiro.titulos-pagar.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">52</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Manutenção de Títulos a Pagar</h1>
                <p class="text-xs text-gray-400">Financeiro › Títulos a pagar</p>
            </div>
        </div>

        {{-- Abas (fiel ao EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','rateio'=>'Rateio (Centros de Custo)'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form method="POST" action="{{ ($titulo ?? null) ? route('financeiro.titulos-pagar.update', $titulo) : route('financeiro.titulos-pagar.store') }}" class="p-6">
            @csrf
            @if($titulo ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS BÁSICOS ============ --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                @unless($titulo ?? null)
                <label class="flex items-center gap-3 text-sm border rounded-lg px-4 py-3 bg-gray-50">
                    <input type="checkbox" name="criar_liquidado" value="1" class="rounded text-primary-600 w-5 h-5">
                    <span class="text-gray-700">Criar o título já liquidado?</span>
                </label>
                @endunless

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <input type="text" name="descricao" value="{{ old('descricao', $titulo->descricao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                            <input type="number" step="0.01" min="0.01" name="valor_original" value="{{ old('valor_original', $titulo->valor_original ?? '') }}" required class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm">
                        </div>
                    </div>
                    @unless($titulo ?? null)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade de Parcelas</label>
                        <input type="number" min="1" max="120" name="quantidade_parcelas" value="{{ old('quantidade_parcelas', 1) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    @endunless
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Credor <span class="text-red-500">*</span></label>
                        <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected(old('pessoa_id', $titulo->pessoa_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Categoria do Título</label>
                        <select name="categoria_pagar_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($categorias as $c)<option value="{{ $c->id }}" @selected(old('categoria_pagar_id', $titulo->categoria_pagar_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                        <select name="forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($formasPagamento as $fp)<option value="{{ $fp->nome }}" @selected(old('forma_pagamento', $titulo->forma_pagamento ?? '')===$fp->nome)>{{ $fp->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Plano de Conta (Despesa)</label>
                        <select name="plano_conta_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                            <option value="">Selecione...</option>
                            @foreach($planosConta as $pc)<option value="{{ $pc->id }}" @selected(old('plano_conta_id', $titulo->plano_conta_id ?? '')==$pc->id)>{{ $pc->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Emissão <span class="text-red-500">*</span></label>
                        <input type="date" name="data_emissao" value="{{ old('data_emissao', optional($titulo->data_emissao ?? null)->format('Y-m-d') ?: date('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vencimento <span class="text-red-500">*</span></label>
                        <input type="date" name="data_vencimento" value="{{ old('data_vencimento', optional($titulo->data_vencimento ?? null)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referência</label>
                        <input type="month" name="referencia" value="{{ old('referencia', $titulo->referencia ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nº Documento</label>
                        <input type="text" name="numero_documento" value="{{ old('numero_documento', $titulo->numero_documento ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Linha digitável</label>
                        <input type="text" name="linha_digitavel" value="{{ old('linha_digitavel', $titulo->linha_digitavel ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Informações Adicionais</label>
                    <textarea name="observacoes" rows="3" maxlength="2000" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacoes', $titulo->observacoes ?? '') }}</textarea>
                </div>
            </div>

            {{-- ============ RATEIO (CENTROS DE CUSTO) ============ --}}
            <div x-show="tab==='rateio'" x-cloak class="space-y-3">
                <div class="flex justify-between items-center">
                    <p class="text-sm text-gray-500">Distribua o valor do título entre centros de custo.</p>
                    <button type="button" @click="addRateio()" class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-xs font-medium hover:bg-primary-700"><i class="fa-solid fa-plus mr-1"></i> Centro de Custo</button>
                </div>
                <template x-for="(r,i) in rateios" :key="i">
                    <div class="border rounded-lg p-3 bg-gray-50 grid grid-cols-1 md:grid-cols-6 gap-2 items-center">
                        <select :name="`rateios[${i}][centro_custo_id]`" x-model="r.centro_custo_id" class="border rounded px-2 py-1.5 text-sm md:col-span-4">
                            <option value="">Centro de custo...</option>
                            @foreach($centrosCusto as $cc)<option value="{{ $cc->id }}">{{ $cc->nome }}</option>@endforeach
                        </select>
                        <input type="number" step="0.01" min="0" :name="`rateios[${i}][valor]`" x-model="r.valor" placeholder="Valor R$" class="border rounded px-2 py-1.5 text-sm">
                        <button type="button" @click="rateios.splice(i,1)" class="p-2 text-red-600 hover:bg-red-50 rounded justify-self-end"><i class="fa-solid fa-trash text-xs"></i></button>
                    </div>
                </template>
                <p x-show="rateios.length===0" class="text-xs text-gray-400 py-2">Nenhum rateio. O título fica sem centro de custo.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('financeiro.titulos-pagar.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function tituloPagarForm(ratIni) {
    return {
        tab: 'basicos',
        rateios: (ratIni||[]).map(r=>({centro_custo_id:r.centro_custo_id??'',valor:r.valor??''})),
        addRateio() { this.rateios.push({centro_custo_id:'',valor:''}); },
    };
}
</script>
@endsection
