@extends('layouts.app')
@section('title', $video ? 'Editar Vídeo' : 'Cadastro de Vídeos')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">301</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $video ? 'Editar Vídeo' : 'Cadastro de Vídeos' }}</h1>
        </div>
        <form action="{{ $video ? route('ead.videos.update', $video) : route('ead.videos.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @if($video) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $video->titulo ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição do seu vídeo <span class="text-red-500">*</span></label>
                <textarea name="descricao" rows="4" maxlength="1000" required class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $video->descricao ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo de vídeo</label>
                <input type="file" name="arquivo" accept="video/mp4,video/webm,video/mpeg,video/ogg" class="w-full border rounded-lg px-3 py-1.5 text-sm">
                <p class="text-xs text-gray-500 mt-1">Suporta: mp4, webm, mpeg, ogv.
                    @if($video?->arquivo) Atual: <a href="{{ asset('storage/'.$video->arquivo) }}" target="_blank" class="text-blue-600 hover:underline">ver vídeo atual</a>.@endif
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.videos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
