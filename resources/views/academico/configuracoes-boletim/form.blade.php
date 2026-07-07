@extends('layouts.app')
@section('title', isset($config) ? 'Editar Configuração' : 'Nova Configuração')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($config) ? 'Editar Configuração do Boletim' : 'Nova Configuração do Boletim' }}</h2>
            <a href="{{ route('academico.configuracoes-boletim.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($config) ? route('academico.configuracoes-boletim.update', $config) : route('academico.configuracoes-boletim.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($config)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $config->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Média de Aprovação <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="media_aprovacao" value="{{ old('media_aprovacao', $config->media_aprovacao ?? '7') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequência Mínima (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" max="100" name="frequencia_minima" value="{{ old('frequencia_minima', $config->frequencia_minima ?? '75') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fórmula (descritiva)</label>
                <input type="text" name="formula" value="{{ old('formula', $config->formula ?? '') }}" placeholder="Ex.: (P1*2 + P2*2 + Trab) / 5" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-400 mt-1">Campo informativo. O cálculo usa os pesos definidos na Tabela de Avaliação.</p>
            </div>

            {{-- Modelos de recuperação dos docs do EDUQ (Graduação / Pós / Livres) --}}
            <div class="border rounded-lg p-4 bg-gray-50 space-y-3" x-data="{ modelo: '{{ old('modelo', $config->modelo ?? 'direto') }}' }">
                <label class="block text-sm font-medium text-gray-700">Modelo de Recuperação (Média Final)</label>
                <select name="modelo" x-model="modelo" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="direto">Sem recuperação — Média Final = Média Parcial (Cursos Livres)</option>
                    <option value="recuperacao_media">Recuperação com média — MF = (M1 + REC) / 2 (Graduação)</option>
                    <option value="recuperacao_substitui">Recuperação substitui — MF = REC (Pós-Graduação)</option>
                </select>
                <div x-show="modelo !== 'direto'" x-cloak class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">REC abre de (M1 ≥)</label>
                        <input type="number" step="0.01" min="0" name="rec_min" value="{{ old('rec_min', $config->rec_min ?? '0') }}" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">até (M1 ≤)</label>
                        <input type="number" step="0.01" min="0" name="rec_max" value="{{ old('rec_max', $config->rec_max ?? '5.99') }}" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Aprovação pós-REC</label>
                        <input type="number" step="0.01" min="0" name="media_aprovacao_final" value="{{ old('media_aprovacao_final', $config->media_aprovacao_final ?? '5') }}" class="w-full border rounded-lg px-2 py-1.5 text-sm">
                    </div>
                </div>
                <p class="text-xs text-gray-400" x-show="modelo !== 'direto'" x-cloak>A recuperação só é liberada automaticamente se a Média Parcial (M1) ficar na faixa acima. Se a REC for nula (aluno passou direto), a Média Final repete a M1. Marque o item "REC" na Tabela de Avaliação.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">
                    {{ isset($config) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('academico.configuracoes-boletim.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
