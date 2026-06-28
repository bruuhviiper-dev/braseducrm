@extends('layouts.app')
@section('title', isset($questionario) ? 'Editar Questionário' : 'Novo Questionário')

@php $tipos = \App\Models\Questionario::tipos(); @endphp

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($questionario) ? 'Editar Questionário' : 'Novo Questionário' }}</h2>
            <a href="{{ route('geral.questionarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($questionario) ? route('geral.questionarios.update', $questionario) : route('geral.questionarios.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($questionario)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $questionario->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($tipos as $val => $label)
                    <option value="{{ $val }}" {{ old('tipo', $questionario->tipo ?? 'avulso') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $questionario->descricao ?? '') }}</textarea>
            </div>

            {{-- Seleção de questões --}}
            <div class="border-t pt-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Questões do Questionário</h3>
                @php $selecionadas = isset($questionario) ? $questionario->questoes->pluck('id')->toArray() : []; @endphp
                <div class="max-h-72 overflow-y-auto border rounded-lg divide-y">
                    @forelse($questoes as $q)
                    <label class="flex items-start gap-2 px-3 py-2 hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="questoes[]" value="{{ $q->id }}" {{ in_array($q->id, old('questoes', $selecionadas)) ? 'checked' : '' }} class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($q->enunciado, 90) }}</span>
                    </label>
                    @empty
                    <p class="px-3 py-4 text-sm text-gray-400 text-center">Nenhuma questão disponível. Cadastre questões primeiro.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $questionario->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 mt-4">
                    {{ isset($questionario) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('geral.questionarios.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 mt-4">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
