@extends('layouts.app')
@section('title', isset($requerimento) ? 'Editar Requerimento' : 'Novo Requerimento')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($requerimento) ? 'Editar Requerimento' : 'Novo Requerimento' }}</h2>
            <a href="{{ route('requerimentos.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($requerimento) ? route('requerimentos.update', $requerimento) : route('requerimentos.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($requerimento)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aluno <span class="text-red-500">*</span></label>
                    <select name="aluno_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)
                        <option value="{{ $a->id }}" {{ old('aluno_id', $requerimento->aluno_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->pessoa?->nome }} {{ $a->ra ? '(RA '.$a->ra.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Requerimento <span class="text-red-500">*</span></label>
                    <select name="tipo_requerimento_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($tipos as $t)
                        <option value="{{ $t->id }}" {{ old('tipo_requerimento_id', $requerimento->tipo_requerimento_id ?? '') == $t->id ? 'selected' : '' }}>{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach(['pendente','aprovado','reprovado','cancelado','entregue'] as $s)
                    <option value="{{ $s }}" {{ old('situacao', $requerimento->situacao ?? 'pendente') == $s ? 'selected' : '' }} class="capitalize">{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao', $requerimento->descricao ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observacoes" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observacoes', $requerimento->observacoes ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($requerimento) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('requerimentos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
