@extends('layouts.app')
@section('title', $modelo ? 'Editar Modelo' : 'Novo Modelo de Documento')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">9</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $modelo ? 'Editar Modelo' : 'Novo Modelo de Documento' }}</h1>
        </div>
        <form action="{{ $modelo ? route('geral.modelos-documento.update', $modelo) : route('geral.modelos-documento.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($modelo) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $modelo->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\ModeloDocumento::TIPOS as $k => $v)<option value="{{ $k }}" @selected(old('tipo', $modelo->tipo ?? '')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo <span class="text-red-500">*</span></label>
                <textarea name="conteudo" rows="12" required class="w-full border rounded-lg px-3 py-2 text-sm font-mono">{{ old('conteudo', $modelo->conteudo ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Use variáveis como <code>[aluno]</code>, <code>[curso]</code>, <code>[data]</code> que serão substituídas na emissão.</p>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $modelo->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600"> Ativo
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('geral.modelos-documento.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
