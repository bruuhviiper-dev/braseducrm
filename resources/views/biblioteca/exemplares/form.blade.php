@extends('layouts.app')
@section('title', $exemplar ? 'Editar Exemplar' : 'Cadastro de Exemplares')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">286</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $exemplar ? 'Editar Exemplar' : 'Cadastro de Exemplares' }}</h1>
        </div>
        <form action="{{ $exemplar ? route('biblioteca.exemplares.update', $exemplar) : route('biblioteca.exemplares.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($exemplar) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Obra <span class="text-red-500">*</span></label>
                    <select name="obra_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($obras as $o)<option value="{{ $o->id }}" {{ (string)old('obra_id', $exemplar->obra_id ?? '') === (string)$o->id ? 'selected' : '' }}>{{ $o->titulo }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biblioteca <span class="text-red-500">*</span></label>
                    <select name="biblioteca_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($bibliotecas as $b)<option value="{{ $b->id }}" {{ (string)old('biblioteca_id', $exemplar->biblioteca_id ?? '') === (string)$b->id ? 'selected' : '' }}>{{ $b->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            @if($exemplar)
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código (tombo)</label>
                    <input type="text" name="codigo" value="{{ old('codigo', $exemplar->codigo) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\Exemplar::SITUACOES as $s)<option value="{{ $s }}" {{ old('situacao', $exemplar->situacao) === $s ? 'selected' : '' }} class="capitalize">{{ ucfirst($s) }}</option>@endforeach
                    </select>
                </div>
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade de exemplares <span class="text-red-500">*</span></label>
                <input type="number" name="quantidade" value="{{ old('quantidade', 1) }}" min="1" max="500" required class="w-full border rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">Serão gerados N exemplares com código sequencial.</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado de Conservação</label>
                    <select name="estado_conservacao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($estados as $e)<option value="{{ $e->id }}" {{ (string)old('estado_conservacao_id', $exemplar->estado_conservacao_id ?? '') === (string)$e->id ? 'selected' : '' }}>{{ $e->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Doador</label>
                    <select name="doador_pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">—</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}" {{ (string)old('doador_pessoa_id', $exemplar->doador_pessoa_id ?? '') === (string)$p->id ? 'selected' : '' }}>{{ $p->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Aquisição</label>
                    <select name="tipo_aquisicao_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($tiposAquisicao as $t)<option value="{{ $t->id }}" {{ (string)old('tipo_aquisicao_id', $exemplar->tipo_aquisicao_id ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor de Compra</label>
                    <input type="number" step="0.01" min="0" name="valor_compra" value="{{ old('valor_compra', $exemplar->valor_compra ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Aquisição</label>
                    <input type="date" name="data_aquisicao" value="{{ old('data_aquisicao', optional($exemplar?->data_aquisicao)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="copia_local" value="1" {{ old('copia_local', $exemplar->copia_local ?? false) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">É uma cópia local?</span>
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('biblioteca.exemplares.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
