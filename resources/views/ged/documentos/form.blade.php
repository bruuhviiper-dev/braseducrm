@extends('layouts.app')
@section('title', isset($documento) ? 'Editar Documento' : 'Enviar Documento')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($documento) ? 'Editar Documento GED' : 'Enviar Documento GED' }}</h2>
            <a href="{{ route('ged.documentos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($documento) ? route('ged.documentos.update', $documento) : route('ged.documentos.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @if(isset($documento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $documento->titulo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classificação</label>
                <select name="classificacao_ged_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">— Sem classificação —</option>
                    @foreach($classificacoes as $c)
                    <option value="{{ $c->id }}" {{ old('classificacao_ged_id', $documento->classificacao_ged_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo @if(!isset($documento))<span class="text-red-500">*</span>@endif</label>
                <input type="file" name="arquivo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" {{ isset($documento) ? '' : 'required' }}>
                @if(isset($documento) && $documento->arquivo)
                <p class="text-xs text-gray-400 mt-1">Arquivo atual: <a href="{{ Storage::url($documento->arquivo) }}" target="_blank" class="text-primary-600 hover:underline">baixar</a>. Envie um novo para substituir.</p>
                @else
                <p class="text-xs text-gray-400 mt-1">Tamanho máximo: 20 MB.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observacoes" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observacoes', $documento->observacoes ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ isset($documento) ? 'Salvar Alteracoes' : 'Enviar' }}</button>
                <a href="{{ route('ged.documentos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
