@extends('layouts.app')
@section('title', 'Cadastro de Desconto Condicional')

@section('content')
<div class="max-w-3xl mx-auto"
     x-data="{
        itens: {{ isset($desconto) ? $desconto->itens->map(fn($i) => ['id' => $i->id, 'dias' => $i->dias, 'valor' => (float)$i->valor])->values()->toJson() : '[]' }},
        add() { this.itens.push({ id: '', dias: 0, valor: 0 }); },
        remove(idx) { this.itens.splice(idx, 1); }
     }">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">58</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Desconto Condicional</h1>
                <p class="text-xs text-primary-500">Financeiro › Cadastros Essenciais</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados Básicos</span>
        </div>
        <form method="POST" action="{{ isset($desconto) ? route('financeiro.descontos-condicionais.update', $desconto) : route('financeiro.descontos-condicionais.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($desconto)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $desconto->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                    <option value="percentual" {{ old('tipo', $desconto->tipo ?? 'percentual') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                    <option value="valor" {{ old('tipo', $desconto->tipo ?? '') == 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aplicar <span class="text-red-500">*</span></label>
                <select name="aplicar" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                    <option value="Todas as parcelas" {{ old('aplicar', $desconto->aplicar ?? '') == 'Todas as parcelas' ? 'selected' : '' }}>Todas as parcelas</option>
                    <option value="Somente na primeira parcela" {{ old('aplicar', $desconto->aplicar ?? '') == 'Somente na primeira parcela' ? 'selected' : '' }}>Somente na primeira parcela</option>
                </select>
            </div>

            <div class="border rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-4 py-2.5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button type="button" @click="add()" class="w-8 h-8 bg-white border rounded-lg text-cyan-600 hover:bg-cyan-50 flex items-center justify-center"><i class="fa-solid fa-plus text-sm"></i></button>
                        <span class="text-sm font-bold text-gray-700">Dias e Valores</span>
                    </div>
                    <span class="text-xs text-gray-400" x-text="itens.length + ' itens'"></span>
                </div>
                <div class="p-4 space-y-2">
                    <template x-for="(item, idx) in itens" :key="idx">
                        <div class="flex gap-2 items-center">
                            <input type="hidden" :name="`itens[${idx}][id]`" :value="item.id">
                            <div class="flex-1">
                                <input type="number" min="0" :name="`itens[${idx}][dias]`" x-model="item.dias" placeholder="Dias" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                            </div>
                            <div class="flex-1">
                                <input type="number" step="0.01" min="0" :name="`itens[${idx}][valor]`" x-model="item.valor" placeholder="Valor" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
                            </div>
                            <button type="button" @click="remove(idx)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </template>
                    <div x-show="itens.length === 0" class="text-center py-8">
                        <i class="fa-regular fa-folder-open text-3xl text-gray-300 mb-2"></i>
                        <p class="text-sm font-medium text-gray-500">Nada encontrado</p>
                        <p class="text-xs text-gray-400">Nenhum item encontrado.</p>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Desvincular dos planos da 41 (Turmas)</button>
                <button type="button" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Desvincular dos planos da 140 (Matrícula Online)</button>
            </div>

            <div class="flex justify-end pt-3 border-t">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
