@extends('layouts.app')
@section('title', isset($matriz) ? 'Editar Matriz Curricular' : 'Nova Matriz Curricular')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($matriz) ? 'Editar Matriz Curricular' : 'Nova Matriz Curricular' }}</h1>

        <form action="{{ isset($matriz) ? route('academico.matrizes.update', $matriz) : route('academico.matrizes.store') }}" method="POST">
            @csrf
            @if(isset($matriz))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $matriz->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Curso --}}
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">Curso <span class="text-red-500">*</span></label>
                    <select name="curso_id" id="curso_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('curso_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $matriz->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('curso_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Carga Horaria Total --}}
                <div>
                    <label for="carga_horaria_total" class="block text-sm font-medium text-gray-700 mb-1">Carga Horaria Total</label>
                    <input type="number" name="carga_horaria_total" id="carga_horaria_total" value="{{ old('carga_horaria_total', $matriz->carga_horaria_total ?? '') }}" min="0"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('carga_horaria_total') border-red-500 @enderror">
                    @error('carga_horaria_total')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Situacao --}}
                <div>
                    <label for="situacao" class="block text-sm font-medium text-gray-700 mb-1">Situacao <span class="text-red-500">*</span></label>
                    <select name="situacao" id="situacao" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('situacao') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        <option value="rascunho" {{ old('situacao', $matriz->situacao ?? '') === 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                        <option value="ativa" {{ old('situacao', $matriz->situacao ?? '') === 'ativa' ? 'selected' : '' }}>Ativa</option>
                        <option value="finalizada" {{ old('situacao', $matriz->situacao ?? '') === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                    </select>
                    @error('situacao')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observacoes --}}
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $matriz->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('academico.matrizes.index') }}" class="px-4 py-2 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
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
