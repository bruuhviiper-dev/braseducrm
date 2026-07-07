@extends('layouts.app')
@section('title', $estrutura ? 'Editar Estrutura do Plano' : 'Nova Estrutura do Plano')

@php $selecionados = $estrutura ? $estrutura->topicos->pluck('id')->all() : []; @endphp

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">204</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $estrutura ? 'Editar' : 'Nova' }} Estrutura do Plano</h1>
        </div>

        <form action="{{ $estrutura ? route('academico.estruturas-plano.update', $estrutura) : route('academico.estruturas-plano.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @if($estrutura) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $estrutura->nome ?? '') }}" required
                       class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tópicos da estrutura</label>
                @if($topicos->isEmpty())
                <p class="text-sm text-gray-400">Nenhum tópico cadastrado. Cadastre tópicos em <strong>Cadastro de Tópico do Plano</strong> primeiro.</p>
                @else
                <p class="text-xs text-gray-500 mb-2">A ordem de marcação define a ordem dos tópicos no plano.</p>
                <div class="space-y-1 border rounded-lg p-3 max-h-80 overflow-y-auto">
                    @foreach($topicos as $t)
                    <label class="flex items-center gap-2 py-1 cursor-pointer">
                        <input type="checkbox" name="topicos[]" value="{{ $t->id }}" {{ in_array($t->id, old('topicos', $selecionados)) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm text-gray-700">{{ $t->nome }}</span>
                        @if($t->obrigatoria)<span class="text-[10px] bg-amber-100 text-amber-700 px-1.5 rounded-full">obrigatória</span>@endif
                    </label>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.estruturas-plano.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
