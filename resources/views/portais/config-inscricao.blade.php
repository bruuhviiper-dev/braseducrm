@extends('layouts.app')
@section('title', 'Configuração (Portal de Inscrição)')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-2 pt-1 pb-3 flex items-start gap-2">
            <span class="text-base font-semibold text-gray-400 mt-0.5">92</span>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Configuração (Portal de Inscrição)</h1>
                <p class="text-xs text-gray-400">Portais › Configuração</p>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('portais.config-inscricao.update') }}" class="space-y-4 max-w-3xl px-2 pb-4"
              x-data="{ cpf: {{ $config->exigir_cpf ? 'true' : 'false' }}, cupom: {{ $config->permitir_cupom ? 'true' : 'false' }} }">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título do portal</label>
                <input type="text" name="titulo" value="{{ old('titulo', $config->titulo) }}" placeholder="Ex.: Inscrições BrasEdu" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cor primária (identidade visual)</label>
                <input type="text" name="cor_primaria" value="{{ old('cor_primaria', $config->cor_primaria) }}" placeholder="#06b6d4" class="w-full md:w-60 border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto de boas-vindas</label>
                <textarea name="texto_boas_vindas" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('texto_boas_vindas', $config->texto_boas_vindas) }}</textarea>
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="exigir_cpf" :value="cpf ? 1 : 0">
                <button type="button" @click="cpf = !cpf" :class="cpf ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="cpf ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Exigir CPF na abertura da inscrição (valida em tempo real)</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="permitir_cupom" :value="cupom ? 1 : 0">
                <button type="button" @click="cupom = !cupom" :class="cupom ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="cupom ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Permitir cupom de desconto no portal</span>
            </label>

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
