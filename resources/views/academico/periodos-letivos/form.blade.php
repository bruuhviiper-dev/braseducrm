@extends('layouts.app')
@section('title', isset($periodo) ? 'Editar Periodo Letivo' : 'Novo Periodo Letivo')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($periodo) ? 'Editar Periodo Letivo' : 'Novo Periodo Letivo' }}</h1>

        <form action="{{ isset($periodo) ? route('academico.periodos-letivos.update', $periodo) : route('academico.periodos-letivos.store') }}" method="POST">
            @csrf
            @if(isset($periodo))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $periodo->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição para o Histórico</label>
                    <input type="text" name="descricao_historico" value="{{ old('descricao_historico', $periodo->descricao_historico ?? ($periodos_letivo->descricao_historico ?? '')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                

                {{-- Data Inicio --}}
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Início das aulas <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', isset($periodo) && $periodo->data_inicio ? $periodo->data_inicio->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_inicio') border-red-500 @enderror">
                    @error('data_inicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Data Fim --}}
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Fim das aulas <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim', isset($periodo) && $periodo->data_fim ? $periodo->data_fim->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('data_fim') border-red-500 @enderror">
                    @error('data_fim')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ativo --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1"
                               {{ old('ativo', $periodo->ativo ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.periodos-letivos.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
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
