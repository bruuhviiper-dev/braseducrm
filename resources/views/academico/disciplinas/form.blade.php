@extends('layouts.app')
@section('title', isset($disciplina) ? 'Editar Disciplina' : 'Nova Disciplina')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($disciplina) ? 'Editar Disciplina' : 'Nova Disciplina' }}</h1>

        <form action="{{ isset($disciplina) ? route('academico.disciplinas.update', $disciplina) : route('academico.disciplinas.store') }}" method="POST">
            @csrf
            @if(isset($disciplina))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $disciplina->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Sigla --}}
                <div>
                    <label for="sigla" class="block text-sm font-medium text-gray-700 mb-1">Sigla <span class="text-red-500">*</span></label>
                    <input type="text" name="sigla" id="sigla" value="{{ old('sigla', $disciplina->sigla ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('sigla') border-red-500 @enderror">
                    @error('sigla')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Carga Horaria --}}
                <div>
                    <label for="carga_horaria" class="block text-sm font-medium text-gray-700 mb-1">Carga Horaria</label>
                    <input type="number" name="carga_horaria" id="carga_horaria" value="{{ old('carga_horaria', $disciplina->carga_horaria ?? '') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('carga_horaria') border-red-500 @enderror">
                    @error('carga_horaria')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ementa --}}
                <div class="md:col-span-2">
                    <label for="ementa" class="block text-sm font-medium text-gray-700 mb-1">Ementa</label>
                    <textarea name="ementa" id="ementa" rows="4"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('ementa') border-red-500 @enderror">{{ old('ementa', $disciplina->ementa ?? '') }}</textarea>
                    @error('ementa')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ativo --}}
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1"
                               {{ old('ativo', $disciplina->ativo ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.disciplinas.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
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
