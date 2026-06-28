@extends('layouts.app')
@section('title', isset($interessado) ? 'Editar Interessado' : 'Novo Interessado')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="p-5 border-b flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">108</span>
                <h1 class="text-lg font-semibold text-gray-800">{{ isset($interessado) ? 'Editar Interessado' : 'Novo Interessado' }}</h1>
            </div>
            <a href="{{ route('crm.interessados.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ isset($interessado) ? route('crm.interessados.update', $interessado) : route('crm.interessados.store') }}" class="p-5">
            @csrf
            @if(isset($interessado))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nome --}}
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome', $interessado->nome ?? '') }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('nome') border-red-500 @enderror">
                    @error('nome')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $interessado->email ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telefone --}}
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input type="text" name="telefone" id="telefone" value="{{ old('telefone', $interessado->telefone ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('telefone') border-red-500 @enderror">
                    @error('telefone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Celular --}}
                <div>
                    <label for="celular" class="block text-sm font-medium text-gray-700 mb-1">Celular</label>
                    <input type="text" name="celular" id="celular" value="{{ old('celular', $interessado->celular ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('celular') border-red-500 @enderror">
                    @error('celular')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Origem --}}
                <div>
                    <label for="origem_id" class="block text-sm font-medium text-gray-700 mb-1">Origem</label>
                    <select name="origem_id" id="origem_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('origem_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($origens as $origem)
                            <option value="{{ $origem->id }}" {{ old('origem_id', $interessado->origem_id ?? '') == $origem->id ? 'selected' : '' }}>
                                {{ $origem->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('origem_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Categoria --}}
                <div>
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_id" id="categoria_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('categoria_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $interessado->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Curso --}}
                <div>
                    <label for="curso_id" class="block text-sm font-medium text-gray-700 mb-1">Curso de Interesse</label>
                    <select name="curso_id" id="curso_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('curso_id') border-red-500 @enderror">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}" {{ old('curso_id', $interessado->curso_id ?? '') == $curso->id ? 'selected' : '' }}>
                                {{ $curso->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('curso_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ativo (only on edit) --}}
                @if(isset($interessado))
                <div class="flex items-center gap-2 pt-6">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" name="ativo" id="ativo" value="1" {{ old('ativo', $interessado->ativo) ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="ativo" class="text-sm font-medium text-gray-700">Ativo</label>
                </div>
                @endif

                {{-- Observacoes --}}
                <div class="md:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 mb-1">Observacoes</label>
                    <textarea name="observacoes" id="observacoes" rows="3"
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $interessado->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('crm.interessados.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    <i class="fa-solid fa-check mr-1"></i> {{ isset($interessado) ? 'Atualizar' : 'Cadastrar' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
