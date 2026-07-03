@extends('layouts.app')
@section('title', 'Cadastro de Curso')

@section('content')
<div class="max-w-3xl mx-auto"
     x-data="{
        bloqMenores: {{ old('bloquear_menores', $curso->bloquear_menores ?? false) ? 'true' : 'false' }},
        naoGerarNf: {{ old('nao_gerar_nf', $curso->nao_gerar_nf ?? false) ? 'true' : 'false' }}
     }">
    <div class="bg-white rounded-xl border">
        <div class="px-5 py-3 border-b flex items-center gap-3">
            <a href="{{ route('academico.cursos.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-semibold text-gray-400">25</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Curso</h1>
                <p class="text-xs text-primary-500">Acadêmico › Cursos</p>
            </div>
        </div>
        <div class="px-5 pt-3 border-b">
            <span class="inline-block pb-2 text-sm font-semibold text-cyan-600 border-b-2 border-cyan-500">Dados Básicos</span>
        </div>
        <form method="POST" action="{{ ($curso ?? null) ? route('academico.cursos.update', $curso) : route('academico.cursos.store') }}" class="p-5 space-y-4">
            @csrf
            @if($curso ?? null) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA</label>
                <input type="text" name="sigla" value="{{ old('sigla', $curso->sigla ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $curso->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Modelo de Documento (Contrato)</label>
                <select name="modelo_documento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach($modelos as $m)
                    <option value="{{ $m->id }}" @selected(old('modelo_documento_id', $curso->modelo_documento_id ?? '') == $m->id)>{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor da comissão</label>
                <input type="number" step="0.01" min="0" name="valor_comissao" value="{{ old('valor_comissao', $curso->valor_comissao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="bloquear_menores" :value="bloqMenores ? 1 : 0">
                <button type="button" @click="bloqMenores = !bloqMenores" :class="bloqMenores ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="bloqMenores ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Bloquear matrícula de alunos menores de idade?</span>
            </label>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="nao_gerar_nf" :value="naoGerarNf ? 1 : 0">
                    <button type="button" @click="naoGerarNf = !naoGerarNf" :class="naoGerarNf ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                        <span :class="naoGerarNf ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                    </button>
                    <span class="text-sm font-medium text-gray-700">Não gerar notas fiscais automaticamente para esse curso</span>
                </label>
                <p class="text-xs text-gray-400 mt-1 ml-[52px]">Com essa opção ativa, parcelas deste curso não serão enviadas para geração de nota fiscal. A geração automática é configurada na função 59 - Configuração do Financeiro.</p>
            </div>

            <input type="hidden" name="ativo" value="1">

            <div class="flex justify-end pt-3 border-t">
                <button type="submit" class="px-6 py-2 bg-cyan-500 hover:bg-cyan-400 text-white rounded-lg text-sm font-semibold"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
