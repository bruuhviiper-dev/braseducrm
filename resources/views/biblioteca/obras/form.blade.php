@extends('layouts.app')
@section('title', $obra ? 'Editar Obra' : 'Nova Obra')

@php $autoresSel = $obra ? $obra->autores->pluck('id')->all() : []; @endphp

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">288</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $obra ? 'Editar' : 'Nova' }} Obra</h1>
        </div>
        <form action="{{ $obra ? route('biblioteca.obras.update', $obra) : route('biblioteca.obras.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @if($obra) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN / ISSN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $obra->isbn ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capa</label>
                    <input type="file" name="capa" accept="image/*" class="w-full border rounded-lg px-3 py-1.5 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $obra->titulo ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo</label>
                <input type="text" name="subtitulo" value="{{ old('subtitulo', $obra->subtitulo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                $selects = [
                    ['editor_id', 'Editor', $editores, 'editor_id'],
                    ['area_conhecimento_id', 'Área de Conhecimento', $areas, 'area_conhecimento_id'],
                    ['idioma_id', 'Idioma', $idiomas, 'idioma_id'],
                    ['tipo_material_id', 'Tipo de Material', $tiposMaterial, 'tipo_material_id'],
                    ['colecao_id', 'Coleção', $colecoes, 'colecao_id'],
                ];
                @endphp
                @foreach($selects as [$name, $label, $opcoes, $attr])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <select name="{{ $name }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($opcoes as $op)
                        <option value="{{ $op->id }}" {{ (string)old($name, $obra->$attr ?? '') === (string)$op->id ? 'selected' : '' }}>{{ $op->nome }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Autores</label>
                @if($autores->isEmpty())
                <p class="text-sm text-gray-400">Nenhum autor cadastrado. Cadastre em <strong>Cadastro de Autores</strong>.</p>
                @else
                <div class="border rounded-lg p-3 max-h-48 overflow-y-auto grid grid-cols-2 gap-1">
                    @foreach($autores as $a)
                    <label class="flex items-center gap-2 py-1 cursor-pointer">
                        <input type="checkbox" name="autores[]" value="{{ $a->id }}" {{ in_array($a->id, old('autores', $autoresSel)) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-700">{{ $a->nome_completo }}</span>
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('biblioteca.obras.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
