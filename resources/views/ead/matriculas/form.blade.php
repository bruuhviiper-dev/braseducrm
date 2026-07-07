@extends('layouts.app')
@section('title', $matricula ? 'Editar Matrícula EAD' : 'Nova Matrícula EAD')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">156</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $matricula ? 'Editar' : 'Nova' }} Matrícula EAD</h1>
        </div>
        <form action="{{ $matricula ? route('ead.matriculas.update', $matricula) : route('ead.matriculas.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($matricula) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="ativo" value="1" {{ old('ativo', $matricula->ativo ?? true) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">Ativo</span>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aluno <span class="text-red-500">*</span></label>
                    <select name="aluno_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)<option value="{{ $a->id }}" {{ (string)old('aluno_id', $matricula->aluno_id ?? '') === (string)$a->id ? 'selected' : '' }}>{{ $a->pessoa?->nome ?? ('Aluno #'.$a->id) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso EAD <span class="text-red-500">*</span></label>
                    <select name="curso_ead_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($cursos as $c)<option value="{{ $c->id }}" {{ (string)old('curso_ead_id', $matricula->curso_ead_id ?? '') === (string)$c->id ? 'selected' : '' }}>{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Matrícula <span class="text-red-500">*</span></label>
                    <input type="date" name="data_matricula" value="{{ old('data_matricula', optional($matricula?->data_matricula)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['ativa','concluida','cancelada','trancada'] as $s)<option value="{{ $s }}" {{ old('situacao', $matricula->situacao ?? 'ativa') === $s ? 'selected' : '' }} class="capitalize">{{ ucfirst($s) }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-2 border-t pt-3">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="matricular_por_agrupador" value="1" {{ old('matricular_por_agrupador', $matricula->matricular_por_agrupador ?? false) ? 'checked' : '' }} class="rounded text-primary-600">
                    Realizar matrícula pelo agrupador?
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="nao_enviar_email" value="1" {{ old('nao_enviar_email', $matricula->nao_enviar_email ?? false) ? 'checked' : '' }} class="rounded text-primary-600">
                    Não enviar o e-mail de criação da matrícula?
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="permitir_inadimplente" value="1" {{ old('permitir_inadimplente', $matricula->permitir_inadimplente ?? false) ? 'checked' : '' }} class="rounded text-primary-600">
                    Permitir o aluno acessar o curso mesmo estando inadimplente?
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="apresentar_nao_confirmado" value="1" {{ old('apresentar_nao_confirmado', $matricula->apresentar_nao_confirmado ?? false) ? 'checked' : '' }} class="rounded text-primary-600">
                    Apresentar curso mesmo que não esteja confirmado na 23 - Matrícula e Histórico?
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.matriculas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
