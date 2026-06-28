@extends('layouts.app')
@section('title', isset($atendimento) ? 'Editar Atendimento' : 'Novo Atendimento')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($atendimento) ? 'Editar Atendimento' : 'Novo Atendimento' }}</h2>
            <a href="{{ route('atendimentos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($atendimento) ? route('atendimentos.update', $atendimento) : route('atendimentos.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($atendimento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)
                        <option value="{{ $p->id }}" {{ old('pessoa_id', $atendimento->pessoa_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <select name="categoria_atendimento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        @foreach($categorias as $c)
                        <option value="{{ $c->id }}" {{ old('categoria_atendimento_id', $atendimento->categoria_atendimento_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="aberto" {{ old('situacao', $atendimento->situacao ?? 'aberto') == 'aberto' ? 'selected' : '' }}>Aberto</option>
                    <option value="em_andamento" {{ old('situacao', $atendimento->situacao ?? '') == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                    <option value="concluido" {{ old('situacao', $atendimento->situacao ?? '') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="falha" {{ old('situacao', $atendimento->situacao ?? '') == 'falha' ? 'selected' : '' }}>Falha</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                <textarea name="descricao" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('descricao', $atendimento->descricao ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Resolução</label>
                <textarea name="resolucao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('resolucao', $atendimento->resolucao ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($atendimento) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('atendimentos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
