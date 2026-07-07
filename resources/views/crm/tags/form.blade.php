@extends('layouts.app')
@section('title', isset($tag) ? 'Editar Tag' : 'Nova Tag')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($tag) ? 'Editar Tag' : 'Nova Tag CRM' }}</h2>
            <a href="{{ route('crm.tags.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($tag) ? route('crm.tags.update', $tag) : route('crm.tags.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($tag)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $tag->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cor <span class="text-red-500">*</span></label>
                <input type="color" name="cor" value="{{ old('cor', $tag->cor ?? '#3B82F6') }}" class="w-20 h-10 border rounded-lg cursor-pointer">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">
                    {{ isset($tag) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('crm.tags.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
