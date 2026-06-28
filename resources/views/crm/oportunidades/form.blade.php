@extends('layouts.app')
@section('title', isset($oportunidade) ? 'Editar Oportunidade' : 'Nova Oportunidade')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="p-5 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">109</span>
                <h1 class="text-lg font-semibold text-gray-800">{{ isset($oportunidade) ? 'Editar Oportunidade' : 'Nova Oportunidade' }}</h1>
            </div>
            <a href="{{ route('crm.oportunidades.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ isset($oportunidade) ? route('crm.oportunidades.update', $oportunidade) : route('crm.oportunidades.store') }}" class="p-5"
              x-data="oportunidadeForm()">
            @csrf
            @if(isset($oportunidade))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Interessado --}}
                <div>
                    <label for="interessado_id" class="block text-sm font-medium text-gray-700 mb-1">Interessado <span class="text-red-500">*</span></label>
                    <select name="interessado_id" id="interessado_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('interessado_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($interessados as $interessado)
                            <option value="{{ $interessado->id }}" {{ old('interessado_id', $oportunidade->interessado_id ?? '') == $interessado->id ? 'selected' : '' }}>
                                {{ $interessado->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('interessado_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Titulo --}}
                <div>
                    <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Titulo</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $oportunidade->titulo ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('titulo') border-red-500 @enderror">
                    @error('titulo')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Funil --}}
                <div>
                    <label for="funil_id" class="block text-sm font-medium text-gray-700 mb-1">Funil <span class="text-red-500">*</span></label>
                    <select name="funil_id" id="funil_id" required x-model="funilId" @change="loadEtapas()">
                        <option value="">Selecione...</option>
                        @foreach($funis as $funil)
                            <option value="{{ $funil->id }}" {{ old('funil_id', $oportunidade->funil_id ?? '') == $funil->id ? 'selected' : '' }}>
                                {{ $funil->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('funil_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Etapa do Funil --}}
                <div>
                    <label for="etapa_funil_id" class="block text-sm font-medium text-gray-700 mb-1">Etapa <span class="text-red-500">*</span></label>
                    <select name="etapa_funil_id" id="etapa_funil_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('etapa_funil_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($etapas as $etapa)
                            <option value="{{ $etapa->id }}" data-funil="{{ $etapa->funil_id }}" {{ old('etapa_funil_id', $oportunidade->etapa_funil_id ?? '') == $etapa->id ? 'selected' : '' }}>
                                {{ $etapa->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('etapa_funil_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Consultor --}}
                <div>
                    <label for="consultor_id" class="block text-sm font-medium text-gray-700 mb-1">Consultor</label>
                    <select name="consultor_id" id="consultor_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('consultor_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($consultores as $consultor)
                            <option value="{{ $consultor->id }}" {{ old('consultor_id', $oportunidade->consultor_id ?? '') == $consultor->id ? 'selected' : '' }}>
                                {{ $consultor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('consultor_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Curso --}}
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                    <select name="curso_id" id="curso_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('curso_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $oportunidade->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('curso_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Valor --}}
                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                    <input type="number" name="valor" id="valor" step="0.01" min="0" value="{{ old('valor', $oportunidade->valor ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('valor') border-red-500 @enderror">
                    @error('valor')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Situacao --}}
                <div>
                    <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situacao <span class="text-red-500">*</span></label>
                    <select name="situacao" id="situacao" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('situacao') border-red-500 @enderror">
                        @php
                            $situacaoAtual = old('situacao', $oportunidade->situacao ?? 'aberta');
                        @endphp
                        <option value="aberta" {{ $situacaoAtual == 'aberta' ? 'selected' : '' }}>Aberta</option>
                        <option value="ganha" {{ $situacaoAtual == 'ganha' ? 'selected' : '' }}>Ganha</option>
                        <option value="perdida" {{ $situacaoAtual == 'perdida' ? 'selected' : '' }}>Perdida</option>
                        <option value="pausada" {{ $situacaoAtual == 'pausada' ? 'selected' : '' }}>Pausada</option>
                    </select>
                    @error('situacao')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data Previsao Fechamento --}}
                <div>
                    <label for="data_previsao_fechamento" class="block text-sm font-medium text-gray-700 mb-1">Previsao de Fechamento</label>
                    <input type="date" name="data_previsao_fechamento" id="data_previsao_fechamento"
                           value="{{ old('data_previsao_fechamento', isset($oportunidade) && $oportunidade->data_previsao_fechamento ? $oportunidade->data_previsao_fechamento->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_previsao_fechamento') border-red-500 @enderror">
                    @error('data_previsao_fechamento')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observacoes --}}
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $oportunidade->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('crm.oportunidades.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-check mr-1"></i> {{ isset($oportunidade) ? 'Atualizar' : 'Cadastrar' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function oportunidadeForm() {
    return {
        funilId: '{{ old('funil_id', $oportunidade->funil_id ?? '') }}',
        loadEtapas() {
            const select = document.getElementById('etapa_funil_id');
            const options = select.querySelectorAll('option[data-funil]');
            options.forEach(option => {
                if (this.funilId && option.dataset.funil !== this.funilId) {
                    option.style.display = 'none';
                    if (option.selected) option.selected = false;
                } else {
                    option.style.display = '';
                }
            });
        },
        init() {
            this.loadEtapas();
        }
    };
}
</script>
@endpush
@endsection
