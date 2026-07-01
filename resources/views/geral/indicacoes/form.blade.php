@extends('layouts.app')
@section('title', $indicacao ? 'Editar Indicação' : 'Nova Indicação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">223</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $indicacao ? 'Editar Indicação' : 'Nova Indicação' }}</h1>
        </div>
        <form action="{{ $indicacao ? route('geral.indicacoes.update', $indicacao) : route('geral.indicacoes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($indicacao) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Indicador (aluno) <span class="text-red-500">*</span></label>
                    <select name="aluno_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)<option value="{{ $a->id }}" @selected(old('aluno_id', $indicacao->aluno_id ?? '')==$a->id)>{{ $a->pessoa?->nome ?? ('Aluno #'.$a->id) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do indicado <span class="text-red-500">*</span></label>
                    <input type="text" name="nome_indicado" value="{{ old('nome_indicado', $indicacao->nome_indicado ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone do indicado</label>
                    <input type="text" name="telefone_indicado" value="{{ old('telefone_indicado', $indicacao->telefone_indicado ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail do indicado</label>
                    <input type="email" name="email_indicado" value="{{ old('email_indicado', $indicacao->email_indicado ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campanha</label>
                    <select name="campanha_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($campanhas as $c)<option value="{{ $c->id }}" @selected(old('campanha_id', $indicacao->campanha_id ?? '')==$c->id)>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\Indicacao::STATUS as $k => $v)<option value="{{ $k }}" @selected(old('situacao', $indicacao->situacao ?? 'pendente')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('geral.indicacoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
