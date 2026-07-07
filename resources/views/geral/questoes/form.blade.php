@extends('layouts.app')
@section('title', isset($questao) ? 'Editar Questão' : 'Nova Questão')

@php $tipos = \App\Models\Questao::tipos(); @endphp

@section('content')
<div class="w-full"
     x-data="{
        tipo: '{{ old('tipo', $questao->tipo ?? 'multipla_escolha') }}',
        opcoes: {{ isset($questao) ? $questao->opcoes->map(fn($o) => ['texto' => $o->texto, 'valor' => $o->valor !== null ? (float)$o->valor : ''])->values()->toJson() : '[]' }},
        add() { this.opcoes.push({ texto:'', valor:'' }); },
        remove(i) { this.opcoes.splice(i,1); },
        get usaOpcoes() { return ['multipla_escolha','escala','verdadeiro_falso'].includes(this.tipo); }
     }">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($questao) ? 'Editar Questão' : 'Nova Questão' }}</h2>
            <a href="{{ route('geral.questoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($questao) ? route('geral.questoes.update', $questao) : route('geral.questoes.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($questao)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Enunciado <span class="text-red-500">*</span></label>
                <textarea name="enunciado" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('enunciado', $questao->enunciado ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" x-model="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach($tipos as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                    <select name="tag_questao_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ old('tag_questao_id', $questao->tag_questao_id ?? '') == $tag->id ? 'selected' : '' }}>{{ $tag->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Opções (apenas tipos que usam) --}}
            <div class="border-t pt-4" x-show="usaOpcoes" x-cloak>
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-gray-700">Opções de Resposta</h3>
                    <button type="button" @click="add()" class="text-sm text-primary-600 hover:underline"><i class="fa-solid fa-plus mr-1"></i>Adicionar opção</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(op, i) in opcoes" :key="i">
                        <div class="flex gap-2 items-center">
                            <input type="text" :name="`opcoes[${i}][texto]`" x-model="op.texto" placeholder="Texto da opção" class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="number" step="0.01" :name="`opcoes[${i}][valor]`" x-model="op.valor" placeholder="Valor" class="w-24 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" @click="remove(i)" class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </template>
                    <p x-show="opcoes.length === 0" class="text-sm text-gray-400 text-center py-3">Nenhuma opção. Clique em "Adicionar opção".</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $questao->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativa</label>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30 mt-4">
                    {{ isset($questao) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('geral.questoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 mt-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
