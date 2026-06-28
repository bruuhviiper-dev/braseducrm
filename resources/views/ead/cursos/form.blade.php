@extends('layouts.app')
@section('title', isset($curso) ? 'Editar Curso EAD' : 'Novo Curso EAD')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border p-6">
        <h1 class="text-lg font-semibold text-gray-800 mb-6">{{ isset($curso) ? 'Editar Curso EAD' : 'Novo Curso EAD' }}</h1>

        <form method="POST" action="{{ isset($curso) ? route('ead.cursos.update', $curso) : route('ead.cursos.store') }}">
            @csrf
            @if(isset($curso)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $curso->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                    @error('nome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horaria</label>
                    <input type="number" name="carga_horaria" value="{{ old('carga_horaria', $curso->carga_horaria ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
                    <input type="number" step="0.01" name="valor" value="{{ old('valor', $curso->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descricao</label>
                <textarea name="descricao" rows="4" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">{{ old('descricao', $curso->descricao ?? '') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="ativo" value="1" {{ old('ativo', $curso->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                    Ativo
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('ead.cursos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
