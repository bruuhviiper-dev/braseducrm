@extends('layouts.app')
@section('title', 'Cadastro de Categorias (A Pagar)')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-5 py-3 border-b flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-400">51</span>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Cadastro de Categorias (A Pagar)</h1>
                <p class="text-xs text-primary-500">Financeiro › Cadastros Essenciais</p>
            </div>
        </div>
        <form method="POST" action="{{ isset($categoria) ? route('financeiro.categorias-pagar.update', $categoria) : route('financeiro.categorias-pagar.store') }}" class="p-5 space-y-4">
            @csrf
            @if(isset($categoria)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $categoria->nome ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grupo</label>
                <input type="text" name="grupo" value="{{ old('grupo', $categoria->grupo ?? '') }}" list="grupos-categorias" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-400">
                <datalist id="grupos-categorias">
                    @foreach(\App\Models\CategoriaPagar::whereNotNull('grupo')->distinct()->pluck('grupo') as $g)
                    <option value="{{ $g }}">
                    @endforeach
                </datalist>
            </div>

            <input type="hidden" name="ativo" value="1">

            <div class="flex justify-end pt-3 sticky bottom-4 z-10">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30"><i class="fa-solid fa-check mr-1"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
