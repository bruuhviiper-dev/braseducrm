@extends('layouts.app')
@section('title', isset($conta) ? 'Editar Conta' : 'Nova Conta')

@section('content')
<div class="bg-white rounded-xl border">
    <div class="p-5 border-b flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">50</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ isset($conta) ? 'Editar Conta' : 'Nova Conta' }}</h1>
        </div>
    </div>

    <form method="POST" action="{{ isset($conta) ? route('financeiro.plano-contas.update', $conta) : route('financeiro.plano-contas.store') }}">
        @csrf
        @if(isset($conta))
            @method('PUT')
        @endif

        <div class="p-5">
            {{-- Validation Errors --}}
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Codigo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Codigo *</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $conta->codigo ?? '') }}"
                           placeholder="Ex: 1.1.01"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                </div>

                {{-- Nome --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input type="text" name="nome" value="{{ old('nome', $conta->nome ?? '') }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                </div>

                {{-- Conta Pai --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta Pai</label>
                    <select name="pai_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
                        <option value="">Nenhuma (Conta Raiz)</option>
                        @foreach($pais as $pai)
                            <option value="{{ $pai->id }}" {{ old('pai_id', $conta->pai_id ?? '') == $pai->id ? 'selected' : '' }}>
                                {{ $pai->codigo }} - {{ $pai->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                        <option value="sintetica" {{ old('tipo', $conta->tipo ?? '') == 'sintetica' ? 'selected' : '' }}>Sintetica (Agrupadora)</option>
                        <option value="analitica" {{ old('tipo', $conta->tipo ?? '') == 'analitica' ? 'selected' : '' }}>Analitica (Lancamento)</option>
                    </select>
                </div>

                {{-- Natureza --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Natureza *</label>
                    <select name="natureza" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none" required>
                        <option value="receita" {{ old('natureza', $conta->natureza ?? '') == 'receita' ? 'selected' : '' }}>Receita</option>
                        <option value="despesa" {{ old('natureza', $conta->natureza ?? '') == 'despesa' ? 'selected' : '' }}>Despesa</option>
                    </select>
                </div>

                {{-- Ativo --}}
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="ativo" value="1"
                               {{ old('ativo', $conta->ativo ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Salvar
                </button>
                <a href="{{ route('financeiro.plano-contas.index') }}" class="px-6 py-2 border rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    Cancelar
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
