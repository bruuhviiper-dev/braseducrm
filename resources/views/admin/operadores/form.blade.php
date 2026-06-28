@extends('layouts.app')
@section('title', isset($operador) ? 'Editar Operador' : 'Novo Operador')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($operador) ? 'Editar Operador' : 'Novo Operador' }}</h2>
            <a href="{{ route('admin.operadores.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($operador) ? route('admin.operadores.update', $operador) : route('admin.operadores.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($operador)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $operador->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Login <span class="text-red-500">*</span></label>
                    <input type="text" name="login" value="{{ old('login', $operador->login ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $operador->email ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha @if(!isset($operador))<span class="text-red-500">*</span>@endif</label>
                <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" {{ isset($operador) ? '' : 'required' }} autocomplete="new-password">
                @if(isset($operador))<p class="text-xs text-gray-400 mt-1">Deixe em branco para manter a senha atual.</p>@endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Grupo de Operadores</label>
                    <select name="grupo_operador_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($grupos as $g)
                        <option value="{{ $g->id }}" {{ old('grupo_operador_id', $operador->grupo_operador_id ?? '') == $g->id ? 'selected' : '' }}>{{ $g->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select name="departamento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($departamentos as $d)
                        <option value="{{ $d->id }}" {{ old('departamento_id', $operador->departamento_id ?? '') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $operador->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ isset($operador) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('admin.operadores.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
