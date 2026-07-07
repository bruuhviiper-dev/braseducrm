@extends('layouts.app')
@section('title', $registro ? 'Editar Exame de Nível' : 'Novo Exame de Nível')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">183</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $registro ? 'Editar' : 'Novo' }} Exame de Nível</h1>
        </div>
        <form action="{{ $registro ? route('academico.exames-nivel.update', $registro) : route('academico.exames-nivel.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($registro) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aluno <span class="text-red-500">*</span></label>
                    <select name="aluno_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)<option value="{{ $a->id }}" {{ (string)old('aluno_id', $registro->aluno_id ?? '') === (string)$a->id ? 'selected' : '' }}>{{ $a->pessoa?->nome ?? ('Aluno #'.$a->id) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disciplina <span class="text-red-500">*</span></label>
                    <select name="disciplina_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">Selecione...</option>
                        @foreach($disciplinas as $d)<option value="{{ $d->id }}" {{ (string)old('disciplina_id', $registro->disciplina_id ?? '') === (string)$d->id ? 'selected' : '' }}>{{ $d->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nota</label>
                    <input type="number" step="0.01" min="0" name="nota" value="{{ old('nota', $registro->nota ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @foreach(\App\Models\ExameNivel::SITUACOES as $s)<option value="{{ $s }}" {{ old('situacao', $registro->situacao ?? 'Pendente') === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data do Exame</label>
                    <input type="date" name="data_exame" value="{{ old('data_exame', optional($registro?->data_exame)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.exames-nivel.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
