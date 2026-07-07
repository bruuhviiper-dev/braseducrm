@extends('layouts.app')
@section('title', isset($meta) ? 'Editar Meta' : 'Nova Meta')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($meta) ? 'Editar Meta' : 'Nova Meta CRM' }}</h2>
            <a href="{{ route('crm.metas.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($meta) ? route('crm.metas.update', $meta) : route('crm.metas.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($meta)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Meta <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $meta->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Funil</label>
                    <select name="funil_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos os funis</option>
                        @foreach($funis as $f)
                        <option value="{{ $f->id }}" {{ old('funil_id', $meta->funil_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consultor</label>
                    <select name="consultor_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos os consultores</option>
                        @foreach($consultores as $c)
                        <option value="{{ $c->id }}" {{ old('consultor_id', $meta->consultor_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="quantidade" {{ old('tipo', $meta->tipo ?? 'quantidade') == 'quantidade' ? 'selected' : '' }}>Quantidade</option>
                        <option value="valor" {{ old('tipo', $meta->tipo ?? '') == 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Periodo <span class="text-red-500">*</span></label>
                    <select name="periodo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="mensal" {{ old('periodo', $meta->periodo ?? 'mensal') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                        <option value="semanal" {{ old('periodo', $meta->periodo ?? '') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Objetivo <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="meta_valor" value="{{ old('meta_valor', $meta->meta_valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicio <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', isset($meta) ? $meta->data_inicio->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', isset($meta) ? $meta->data_fim->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">
                    {{ isset($meta) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('crm.metas.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
