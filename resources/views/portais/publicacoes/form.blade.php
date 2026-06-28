@extends('layouts.app')
@section('title', isset($publicacao) ? 'Editar Publicação' : 'Nova Publicação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($publicacao) ? 'Editar Publicação' : 'Nova Publicação' }}</h2>
            <a href="{{ route('portais.publicacoes.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($publicacao) ? route('portais.publicacoes.update', $publicacao) : route('portais.publicacoes.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($publicacao)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $publicacao->titulo ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pasta</label>
                    <select name="pasta_portal_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">— Sem pasta —</option>
                        @foreach($pastas as $p)
                        <option value="{{ $p->id }}" {{ old('pasta_portal_id', $publicacao->pasta_portal_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Publicado em</label>
                    <input type="date" name="publicado_em" value="{{ old('publicado_em', isset($publicacao) && $publicacao->publicado_em ? $publicacao->publicado_em->format('Y-m-d') : now()->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conteúdo</label>
                <textarea name="conteudo" rows="6" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('conteudo', $publicacao->conteudo ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $publicacao->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Publicado (visível no portal)</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ isset($publicacao) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('portais.publicacoes.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
