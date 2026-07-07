@extends('layouts.app')
@section('title', isset($funil) ? 'Editar Funil' : 'Novo Funil')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="p-5 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">200</span>
                <h1 class="text-lg font-semibold text-gray-800">{{ isset($funil) ? 'Editar Funil' : 'Novo Funil' }}</h1>
            </div>
            <a href="{{ route('crm.funil.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ isset($funil) ? route('crm.funil.update', $funil) : route('crm.funil.store') }}" class="p-5"
              x-data="funilForm()">
            @csrf
            @if(isset($funil))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome do Funil <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $funil->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Padrao --}}
                <div class="flex items-center gap-2">
                    <input type="hidden" name="padrao" value="0">
                    <input type="checkbox" name="padrao" id="padrao" value="1" {{ old('padrao', $funil->padrao ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="padrao" class="text-sm font-medium text-gray-700">Funil padrao</label>
                </div>

                {{-- Ativo --}}
                <div class="flex items-center gap-2">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $funil->ativo ?? true) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="ativo" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
            </div>

            {{-- Etapas --}}
            <div class="border-t pt-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">
                        <i class="fa-solid fa-layer-group mr-1 text-primary-500"></i> Etapas do Funil
                    </h2>
                    <button type="button" @click="addEtapa()"
                            class="px-3 py-1.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-1">
                        <i class="fa-solid fa-plus"></i> Adicionar Etapa
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(etapa, index) in etapas" :key="index">
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm font-medium text-gray-600">
                                    Etapa <span x-text="index + 1"></span>
                                </span>
                                <button type="button" @click="removeEtapa(index)"
                                        class="text-red-400 hover:text-red-600 text-sm transition">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Remover
                                </button>
                            </div>
                            <input type="hidden" :name="'etapas[' + index + '][id]'" :value="etapa.id || ''">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nome <span class="text-red-500">*</span></label>
                                    <input type="text" :name="'etapas[' + index + '][nome]'" x-model="etapa.nome" required
                                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Cor <span class="text-red-500">*</span></label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" :name="'etapas[' + index + '][cor]'" x-model="etapa.cor"
                                               class="w-10 h-9 border rounded cursor-pointer">
                                        <input type="text" x-model="etapa.cor" maxlength="7"
                                               class="flex-1 border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Ordem <span class="text-red-500">*</span></label>
                                        <input type="number" :name="'etapas[' + index + '][ordem]'" x-model="etapa.ordem" min="1" required
                                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Prazo (dias)</label>
                                        <input type="number" :name="'etapas[' + index + '][prazo_dias]'" x-model="etapa.prazo_dias" min="0"
                                               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="etapas.length === 0" class="text-center text-gray-400 py-8 border rounded-lg border-dashed">
                    <i class="fa-solid fa-layer-group text-3xl mb-2"></i>
                    <p class="text-sm">Nenhuma etapa adicionada. Clique em "Adicionar Etapa" para comecar.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('crm.funil.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30 transition">
                    <i class="fa-solid fa-check mr-1"></i> {{ isset($funil) ? 'Atualizar' : 'Cadastrar' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function funilForm() {
    const defaultColors = ['#3b82f6', '#8b5cf6', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#ec4899', '#6366f1'];

    return {
        etapas: {!! json_encode(old('etapas', isset($funil) ? $funil->etapas->map(fn($e) => ['id' => $e->id, 'nome' => $e->nome, 'cor' => $e->cor, 'ordem' => $e->ordem, 'prazo_dias' => $e->prazo_dias])->toArray() : [])) !!},

        addEtapa() {
            const nextOrder = this.etapas.length + 1;
            const colorIndex = (nextOrder - 1) % defaultColors.length;
            this.etapas.push({
                id: null,
                nome: '',
                cor: defaultColors[colorIndex],
                ordem: nextOrder,
                prazo_dias: null,
            });
        },

        removeEtapa(index) {
            this.etapas.splice(index, 1);
            this.etapas.forEach((etapa, i) => {
                etapa.ordem = i + 1;
            });
        }
    };
}
</script>
@endpush
@endsection
