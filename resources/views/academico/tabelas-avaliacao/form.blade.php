@extends('layouts.app')
@section('title', isset($tabela) ? 'Editar Tabela' : 'Nova Tabela')

@section('content')
<div class="max-w-2xl mx-auto"
     x-data="{
        itens: {{ isset($tabela) ? $tabela->itens->map(fn($i) => ['id' => $i->id, 'nome' => $i->nome, 'peso' => (float)$i->peso])->values()->toJson() : '[]' }},
        add() { this.itens.push({ id: '', nome: '', peso: 1 }); },
        remove(idx) { this.itens.splice(idx, 1); }
     }">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($tabela) ? 'Editar Tabela de Avaliação' : 'Nova Tabela de Avaliação' }}</h2>
            <a href="{{ route('academico.tabelas-avaliacao.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($tabela) ? route('academico.tabelas-avaliacao.update', $tabela) : route('academico.tabelas-avaliacao.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($tabela)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $tabela->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nota Máxima <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="1" name="nota_maxima" value="{{ old('nota_maxima', $tabela->nota_maxima ?? '10') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Média de Aprovação <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="media_aprovacao" value="{{ old('media_aprovacao', $tabela->media_aprovacao ?? '7') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $tabela->descricao ?? '') }}</textarea>
            </div>

            {{-- Itens de avaliação --}}
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-700">Itens de Avaliação (peso)</h3>
                    <button type="button" @click="add()" class="text-sm text-primary-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Adicionar item</button>
                </div>
                <p class="text-xs text-gray-400 mb-3">Ex.: P1 (peso 2), P2 (peso 2), Trabalho (peso 1). A média final é ponderada pelos pesos.</p>
                <div class="space-y-2">
                    <template x-for="(item, idx) in itens" :key="idx">
                        <div class="flex gap-2 items-center">
                            <input type="hidden" :name="`itens[${idx}][id]`" :value="item.id">
                            <input type="text" :name="`itens[${idx}][nome]`" x-model="item.nome" placeholder="Nome do item" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <input type="number" step="0.01" min="0" :name="`itens[${idx}][peso]`" x-model="item.peso" placeholder="Peso" class="w-24 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <button type="button" @click="remove(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </template>
                    <p x-show="itens.length === 0" class="text-sm text-gray-400 text-center py-3">Nenhum item. Clique em "Adicionar item".</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">
                    {{ isset($tabela) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('academico.tabelas-avaliacao.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 mt-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
