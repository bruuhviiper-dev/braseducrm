@extends('layouts.app')
@section('title', isset($titulo) ? 'Editar Titulo a Receber' : 'Novo Titulo a Receber')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">64</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ isset($titulo) ? 'Editar Titulo a Receber' : 'Novo Titulo a Receber' }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ isset($titulo) ? route('financeiro.titulos-receber.update', $titulo) : route('financeiro.titulos-receber.store') }}">
        @csrf
        @if(isset($titulo))
            @method('PUT')
        @endif

        <div class="p-5">
            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Pessoa --}}
                <div x-data="{
                    search: '{{ isset($titulo) ? ($titulo->pessoa->nome ?? '') : '' }}',
                    selected: '{{ old('pessoa_id', $titulo->pessoa_id ?? '') }}',
                    open: false,
                    pessoas: {{ $pessoas->map(fn($p) => ['id' => $p->id, 'nome' => $p->nome])->toJson() }},
                    get filtered() {
                        if (!this.search) return this.pessoas;
                        return this.pessoas.filter(p => p.nome.toLowerCase().includes(this.search.toLowerCase()));
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa *</label>
                    <input type="hidden" name="pessoa_id" x-model="selected">
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                           placeholder="Buscar pessoa..."
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    <div x-show="open && filtered.length > 0" x-cloak
                         class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="pessoa in filtered" :key="pessoa.id">
                            <button type="button"
                                    @click="selected = pessoa.id; search = pessoa.nome; open = false"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50">
                                <span x-text="pessoa.nome"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Categoria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_receber_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Selecione</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_receber_id', $titulo->categoria_receber_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Conta Bancaria --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta Bancaria</label>
                    <select name="conta_bancaria_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Selecione</option>
                        @foreach($contas as $conta)
                            <option value="{{ $conta->id }}" {{ old('conta_bancaria_id', $titulo->conta_bancaria_id ?? '') == $conta->id ? 'selected' : '' }}>
                                {{ $conta->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Valor Original --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Original *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                        <input type="number" name="valor_original" step="0.01" min="0.01"
                               value="{{ old('valor_original', $titulo->valor_original ?? '') }}"
                               class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                    </div>
                </div>

                {{-- Valor Desconto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor Desconto</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">R$</span>
                        <input type="number" name="valor_desconto" step="0.01" min="0"
                               value="{{ old('valor_desconto', $titulo->valor_desconto ?? '') }}"
                               class="w-full border rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                    </div>
                </div>

                {{-- Data Emissao --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Emissao *</label>
                    <input type="date" name="data_emissao"
                           value="{{ old('data_emissao', isset($titulo) && $titulo->data_emissao ? $titulo->data_emissao->format('Y-m-d') : date('Y-m-d')) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                </div>

                {{-- Data Vencimento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Vencimento *</label>
                    <input type="date" name="data_vencimento"
                           value="{{ old('data_vencimento', isset($titulo) && $titulo->data_vencimento ? $titulo->data_vencimento->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                </div>

                {{-- Forma Pagamento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                    <select name="forma_pagamento" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Selecione</option>
                        @foreach(['Boleto', 'PIX', 'Cartao de Credito', 'Cartao de Debito', 'Dinheiro', 'Transferencia', 'Cheque'] as $forma)
                            <option value="{{ $forma }}" {{ old('forma_pagamento', $titulo->forma_pagamento ?? '') == $forma ? 'selected' : '' }}>{{ $forma }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Observacoes --}}
                <div class="lg:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                    <textarea name="observacoes" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">{{ old('observacoes', $titulo->observacoes ?? '') }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Salvar
                </button>
                <a href="{{ route('financeiro.titulos-receber.index') }}" class="px-6 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
