@extends('layouts.app')
@section('title', 'Cadastro de Documento')

@section('content')
<div class="w-full"
     x-data="{
        visMatriz: {{ old('visibilidade_matriz', $documento->visibilidade_matriz ?? false) ? 'true' : 'false' }},
        obrigGen: {{ old('obrigatorio_generos', $documento->obrigatorio_generos ?? true) ? 'true' : 'false' }}
     }">
    <x-eduq-header title="Cadastro de Documento" breadcrumb="Acadêmico › Documentos" :back="route('documentos.index')" />
    <div class="bg-white rounded-xl border p-6 pb-24">
        <form method="POST" action="{{ isset($documento) ? route('documentos.update', $documento) : route('documentos.store') }}" class="space-y-4">
            @csrf
            @if(isset($documento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <x-eduq-toggle name="ativo" :checked="old('ativo', $documento->ativo ?? true)" />

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA</label>
                <input type="text" name="sigla" maxlength="20" value="{{ old('sigla', $documento->sigla ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $documento->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de documento GED</label>
                <select name="tipo_ged" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach(['Documento Pessoal', 'Documento Acadêmico', 'Comprovante', 'Contrato', 'Outros'] as $t)
                    <option value="{{ $t }}" {{ old('tipo_ged', $documento->tipo_ged ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Idade Mínima</label>
                <input type="number" min="0" max="120" name="idade_minima" value="{{ old('idade_minima', $documento->idade_minima ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
            </div>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="visibilidade_matriz" :value="visMatriz ? 1 : 0">
                <button type="button" @click="visMatriz = !visMatriz" :class="visMatriz ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="visMatriz ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Adicionar visibilidade por matriz?</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="obrigatorio_generos" :value="obrigGen ? 1 : 0">
                <button type="button" @click="obrigGen = !obrigGen" :class="obrigGen ? 'bg-cyan-500' : 'bg-gray-300'" class="relative w-10 h-5 rounded-full transition-colors shrink-0">
                    <span :class="obrigGen ? 'translate-x-5' : 'translate-x-0.5'" class="absolute top-0.5 left-0 w-4 h-4 bg-white rounded-full shadow transition-transform"></span>
                </button>
                <span class="text-sm font-medium text-gray-700">Obrigatório para todos os gêneros?</span>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grau</label>
                <select name="grau" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Selecione...</option>
                    @foreach(['Graduação', 'Pós-Graduação', 'Técnico', 'Livre', 'Extensão'] as $g)
                    <option value="{{ $g }}" {{ old('grau', $documento->grau ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cursos</label>
                <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Todos os cursos</option>
                    @foreach($cursos as $c)
                    <option value="{{ $c->id }}" {{ old('curso_id', $documento->curso_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Formas de Ingressos</label>
                <select name="forma_ingresso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">Todas</option>
                    @foreach($formasIngresso as $f)
                    <option value="{{ $f->id }}" {{ old('forma_ingresso_id', $documento->forma_ingresso_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->nome }}</option>
                    @endforeach
                </select>
            </div>

            <x-eduq-save />
        </form>
    </div>
</div>
@endsection
