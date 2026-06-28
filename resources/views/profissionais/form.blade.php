@extends('layouts.app')
@section('title', isset($profissional) ? 'Editar Profissional' : 'Novo Profissional')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($profissional) ? 'Editar Profissional' : 'Novo Profissional' }}</h2>
            <a href="{{ route('profissionais.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($profissional) ? route('profissionais.update', $profissional) : route('profissionais.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($profissional)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)
                    <option value="{{ $p->id }}" {{ old('pessoa_id', $profissional->pessoa_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">A pessoa deve estar cadastrada em Pessoas (11).</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Profissional</label>
                    <select name="tipo_profissional_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($tipos as $t)
                        <option value="{{ $t->id }}" {{ old('tipo_profissional_id', $profissional->tipo_profissional_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Titularidade</label>
                    <select name="titularidade_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($titularidades as $t)
                        <option value="{{ $t->id }}" {{ old('titularidade_id', $profissional->titularidade_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Registro Profissional</label>
                <input type="text" name="registro_profissional" value="{{ old('registro_profissional', $profissional->registro_profissional ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $profissional->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ isset($profissional) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('profissionais.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
