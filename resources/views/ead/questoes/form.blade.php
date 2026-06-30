@extends('layouts.app')
@section('title', $questao ? 'Editar Questão' : 'Nova Questão Avulsa')

@php
    $altIniciais = old('alternativas', $questao?->alternativas->map(fn($a) => ['texto' => $a->texto, 'correta' => (bool) $a->correta])->values()->toArray() ?? []);
@endphp

@section('content')
<div class="max-w-3xl mx-auto" x-data="questaoForm(@js($questao->tipo ?? 'multipla_escolha'), @js($altIniciais))">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">238</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $questao ? 'Editar' : 'Nova' }} Questão Avulsa</h1>
        </div>
        <form action="{{ $questao ? route('ead.questoes.update', $questao) : route('ead.questoes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($questao) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $questao->ativo ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">Ativo</span>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $questao->titulo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Enunciado <span class="text-red-500">*</span></label>
                <textarea name="enunciado" rows="4" required class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('enunciado', $questao->enunciado ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" x-model="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\QuestaoAvulsa::TIPOS as $val => $label)
                        <option value="{{ $val }}" {{ old('tipo', $questao->tipo ?? 'multipla_escolha') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Peso</label>
                    <input type="number" step="0.01" min="0" name="peso" value="{{ old('peso', $questao->peso ?? '') }}" placeholder="1" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tag de Questão</label>
                    <select name="tag_questao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">—</option>
                        @foreach($tags as $t)<option value="{{ $t->id }}" {{ (string)old('tag_questao_id', $questao->tag_questao_id ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            {{-- Alternativas (só múltipla escolha / V-F) --}}
            <div x-show="tipo === 'multipla_escolha' || tipo === 'verdadeiro_falso'" x-cloak class="border-t pt-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-gray-800">Alternativas</h2>
                    <button type="button" @click="adicionar()" class="text-primary-600 hover:text-primary-700 text-sm font-medium"><i class="fa-solid fa-plus mr-1"></i> Adicionar</button>
                </div>
                <p class="text-xs text-gray-500 mb-2">Marque a(s) alternativa(s) correta(s).</p>
                <div class="space-y-2">
                    <template x-for="(alt, i) in alternativas" :key="i">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" :name="`corretas[]`" :value="i" x-model="alt.correta" :true-value="true" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500" title="Correta?">
                            <input type="text" :name="`alternativas[${i}][texto]`" x-model="alt.texto" placeholder="Texto da alternativa" class="flex-1 border rounded px-2 py-1.5 text-sm">
                            <button type="button" @click="remover(i)" class="p-1.5 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash-can text-sm"></i></button>
                        </div>
                    </template>
                    <p x-show="alternativas.length === 0" class="text-sm text-gray-400">Nenhuma alternativa. Clique em Adicionar.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Explicação da resposta (opcional)</label>
                <textarea name="explicacao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('explicacao', $questao->explicacao ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.questoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function questaoForm(tipo, iniciais) {
        return {
            tipo: tipo,
            alternativas: iniciais.length ? iniciais : [{ texto: '', correta: false }, { texto: '', correta: false }],
            adicionar() { this.alternativas.push({ texto: '', correta: false }); },
            remover(i) { this.alternativas.splice(i, 1); },
        };
    }
</script>
@endsection
