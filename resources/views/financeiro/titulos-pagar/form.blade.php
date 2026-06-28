@extends('layouts.app')
@section('title', isset($titulo) ? 'Editar Titulo a Pagar' : 'Novo Titulo a Pagar')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">52</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ isset($titulo) ? 'Editar Titulo a Pagar' : 'Novo Titulo a Pagar' }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ isset($titulo) ? route('financeiro.titulos-pagar.update', $titulo) : route('financeiro.titulos-pagar.store') }}">
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
                    <select name="categoria_pagar_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Selecione</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_pagar_id', $titulo->categoria_pagar_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Descricao --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
                    <input type="text" name="descricao" value="{{ old('descricao', $titulo->descricao ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
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
                <a href="{{ route('financeiro.titulos-pagar.index') }}" class="px-6 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
