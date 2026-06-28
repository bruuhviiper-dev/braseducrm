@extends('layouts.app')
@section('title', isset($grupo) ? 'Editar Grupo' : 'Novo Grupo')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ marcarTodos(modulo, val) { document.querySelectorAll('[data-modulo=\''+modulo+'\']').forEach(c => c.checked = val); } }">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($grupo) ? 'Editar Grupo de Operadores' : 'Novo Grupo de Operadores' }}</h2>
            <a href="{{ route('admin.grupos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($grupo) ? route('admin.grupos.update', $grupo) : route('admin.grupos.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($grupo)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $grupo->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="ativo" value="1" {{ old('ativo', $grupo->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">Ativo</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <input type="text" name="descricao" value="{{ old('descricao', $grupo->descricao ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Matriz de permissões --}}
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Permissões (funções liberadas)</h3>
                @php $sel = $selecionadas ?? []; @endphp
                <div class="space-y-4 max-h-[28rem] overflow-y-auto pr-2">
                    @forelse($funcoesPorModulo as $modulo => $funcoes)
                    <div class="border rounded-lg">
                        <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b">
                            <span class="text-sm font-semibold text-gray-700 capitalize">{{ $modulo }}</span>
                            <div class="flex gap-2 text-xs">
                                <button type="button" @click="marcarTodos('{{ $modulo }}', true)" class="text-primary-600 hover:underline">Todos</button>
                                <button type="button" @click="marcarTodos('{{ $modulo }}', false)" class="text-gray-400 hover:underline">Nenhum</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1 p-3">
                            @foreach($funcoes as $f)
                            <label class="flex items-center gap-2 text-sm text-gray-600 hover:bg-gray-50 rounded px-1 py-0.5">
                                <input type="checkbox" name="funcoes[]" value="{{ $f->id }}" data-modulo="{{ $modulo }}" {{ in_array($f->id, old('funcoes', $sel)) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-gray-400 text-xs">{{ $f->codigo }}</span> {{ $f->nome }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Nenhuma função cadastrada (tabela funcoes vazia).</p>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">{{ isset($grupo) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('admin.grupos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 mt-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
