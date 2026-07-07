@extends('layouts.app')
@section('title', 'Tabela de Avaliação')

@section('content')
<div class="w-full"
     x-data="{
        visOp: {{ old('visibilidade_operador', $tabela->visibilidade_operador ?? false) ? 'true' : 'false' }},
        formula: {{ json_encode(old('formula', $tabela->formula ?? '')) }},
        itens: {{ isset($tabela) ? $tabela->itens->map(fn($i) => ['id' => $i->id, 'nome' => $i->nome, 'peso' => (float)$i->peso])->values()->toJson() : '[]' }},
        add() { this.itens.push({ id: '', nome: '', peso: 1 }); },
        remove(idx) { this.itens.splice(idx, 1); }
     }">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">5</span>
            <h1 class="text-lg font-bold text-gray-800">Tabela de Avaliação</h1>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados básicos</span>
        </div>
        <form method="POST" action="{{ isset($tabela) ? route('academico.tabelas-avaliacao.update', $tabela) : route('academico.tabelas-avaliacao.store') }}" class="p-5 space-y-5">
            @csrf
            @if(isset($tabela)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $tabela->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div class="border-t pt-4">
                <h3 class="text-sm font-bold text-gray-700 mb-3">Definições</h3>
                <label class="flex items-center gap-3 cursor-pointer mb-1">
                    <input type="hidden" name="visibilidade_operador" :value="visOp ? 1 : 0">
                    <button type="button" @click="visOp = !visOp" :class="visOp ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="visOp ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Visibilidade por operador?</span>
                </label>
                <p class="text-xs text-gray-400 mb-3 ml-[52px]">Quando ativo, selecione um operador e somente esse operador terá acesso para ver e editar esta tabela de avaliação.</p>
                <div x-show="visOp" x-cloak class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operador</label>
                    <select name="operador_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        <option value="">Selecione...</option>
                        @foreach($operadores as $op)
                        <option value="{{ $op->id }}" {{ old('operador_id', $tabela->operador_id ?? '') == $op->id ? 'selected' : '' }}>{{ $op->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fórmula</label>
                    <input type="text" name="formula" maxlength="250" x-model="formula" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <p class="text-xs text-gray-400 text-right mt-0.5"><span x-text="(formula || '').length"></span> / 250</p>
                </div>
            </div>

            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold text-gray-700">Avaliações <span class="ml-1 text-xs font-normal text-gray-400" x-text="itens.length + ' itens'"></span></h3>
                    <button type="button" @click="add()" class="text-sm text-cyan-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Adicionar</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(item, idx) in itens" :key="idx">
                        <div class="flex gap-2 items-center">
                            <input type="hidden" :name="`itens[${idx}][id]`" :value="item.id">
                            <input type="text" :name="`itens[${idx}][nome]`" x-model="item.nome" placeholder="Descrição da avaliação" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                            <input type="number" step="0.01" min="0" :name="`itens[${idx}][peso]`" x-model="item.peso" placeholder="Peso" class="w-24 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                            <button type="button" @click="remove(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </template>
                    <div x-show="itens.length === 0" class="text-center py-4">
                        <p class="text-sm font-medium text-gray-500">Nada encontrado</p>
                        <p class="text-xs text-gray-400">Nenhum item encontrado.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
