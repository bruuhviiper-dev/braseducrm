@extends('layouts.app')
@section('title', isset($curso) ? 'Editar Curso' : 'Novo Curso')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($curso) ? 'Editar Curso' : 'Novo Curso' }}</h1>

        <form action="{{ isset($curso) ? route('academico.cursos.update', $curso) : route('academico.cursos.store') }}" method="POST">
            @csrf
            @if(isset($curso))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $curso->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sigla --}}
                <div>
                    <label for="sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla <span class="text-red-500">*</span></label>
                    <input type="text" name="sigla" id="sigla" value="{{ old('sigla', $curso->sigla ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('sigla') border-red-500 @enderror">
                    @error('sigla')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Area de Conhecimento --}}
                <div>
                    <label for="area_conhecimento_id" class="block text-sm font-medium text-gray-700 mb-1">Area de Conhecimento</label>
                    <select name="area_conhecimento_id" id="area_conhecimento_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('area_conhecimento_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ old('area_conhecimento_id', $curso->area_conhecimento_id ?? '') == $area->id ? 'selected' : '' }}>
                                {{ $area->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('area_conhecimento_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grau --}}
                <div>
                    <label for="grau_id" class="block text-sm font-medium text-gray-700 mb-1">Grau</label>
                    <select name="grau_id" id="grau_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('grau_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($graus as $grau)
                            <option value="{{ $grau->id }}" {{ old('grau_id', $curso->grau_id ?? '') == $grau->id ? 'selected' : '' }}>
                                {{ $grau->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('grau_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Habilitacao --}}
                <div>
                    <label for="habilitacao_id" class="block text-sm font-medium text-gray-700 mb-1">Habilitacao</label>
                    <select name="habilitacao_id" id="habilitacao_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('habilitacao_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($habilitacoes as $habilitacao)
                            <option value="{{ $habilitacao->id }}" {{ old('habilitacao_id', $curso->habilitacao_id ?? '') == $habilitacao->id ? 'selected' : '' }}>
                                {{ $habilitacao->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('habilitacao_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Instituicao de Ensino --}}
                <div>
                    <label for="instituicao_ensino_id" class="block text-sm font-medium text-gray-700 mb-1">Instituicao de Ensino</label>
                    <select name="instituicao_ensino_id" id="instituicao_ensino_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('instituicao_ensino_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($instituicoes as $instituicao)
                            <option value="{{ $instituicao->id }}" {{ old('instituicao_ensino_id', $curso->instituicao_ensino_id ?? '') == $instituicao->id ? 'selected' : '' }}>
                                {{ $instituicao->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('instituicao_ensino_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Carga Horaria Total --}}
                <div>
                    <label for="carga_horaria_total" class="block text-sm font-medium text-gray-700 mb-1">Carga Horaria Total</label>
                    <input type="number" name="carga_horaria_total" id="carga_horaria_total" value="{{ old('carga_horaria_total', $curso->carga_horaria_total ?? '') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('carga_horaria_total') border-red-500 @enderror">
                    @error('carga_horaria_total')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Duracao em Meses --}}
                <div>
                    <label for="duracao_meses" class="block text-sm font-medium text-gray-700 mb-1">Duracao (meses)</label>
                    <input type="number" name="duracao_meses" id="duracao_meses" value="{{ old('duracao_meses', $curso->duracao_meses ?? '') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('duracao_meses') border-red-500 @enderror">
                    @error('duracao_meses')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descricao --}}
                <div class="md:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
                    <textarea name="descricao" id="descricao" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('descricao') border-red-500 @enderror">{{ old('descricao', $curso->descricao ?? '') }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ativo --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1"
                               {{ old('ativo', $curso->ativo ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.cursos.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-check mr-1"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
