@extends('layouts.app')
@section('title', $registro ? 'Editar Liberação' : 'Nova Liberação de Frequência')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">262</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $registro ? 'Editar' : 'Nova' }} Liberação de Frequência</h1>
        </div>
        <form action="{{ $registro ? route('academico.liberacoes-frequencia.update', $registro) : route('academico.liberacoes-frequencia.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($registro) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                <select name="turma_montada_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Selecione...</option>
                    @foreach($turmasMontadas as $tm)
                    <option value="{{ $tm->id }}" {{ (string) old('turma_montada_id', $registro->turma_montada_id ?? '') === (string) $tm->id ? 'selected' : '' }}>{{ $tm->nome ?? $tm->turma?->nome ?? ('Turma #'.$tm->id) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Professor</label>
                <select name="profissional_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">— Todos —</option>
                    @foreach($profissionais as $p)
                    <option value="{{ $p->id }}" {{ (string) old('profissional_id', $registro->profissional_id ?? '') === (string) $p->id ? 'selected' : '' }}>{{ $p->pessoa?->nome ?? ('Profissional #'.$p->id) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Início da Liberação <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($registro?->data_inicio)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fim da Liberação <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', optional($registro?->data_fim)->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.liberacoes-frequencia.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
