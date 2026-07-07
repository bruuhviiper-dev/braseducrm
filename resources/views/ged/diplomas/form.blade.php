@extends('layouts.app')
@section('title', isset($diploma) ? 'Editar Diploma' : 'Novo Diploma')

@php $tipos = \App\Models\DiplomaDigital::situacoes(); @endphp

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($diploma) ? 'Editar Diploma Digital' : 'Novo Diploma Digital' }}</h2>
            <a href="{{ route('ged.diplomas.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($diploma) ? route('ged.diplomas.update', $diploma) : route('ged.diplomas.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($diploma)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Matrícula <span class="text-gray-400 text-xs">(deriva aluno e curso)</span></label>
                <select name="matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    @foreach($matriculas as $m)
                    <option value="{{ $m->id }}" {{ old('matricula_id', $diploma->matricula_id ?? '') == $m->id ? 'selected' : '' }}>{{ $m->numero_matricula ?? ('#'.$m->id) }} — {{ $m->aluno?->pessoa?->nome ?? 'Aluno '.$m->aluno_id }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aluno</label>
                    <select name="aluno_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)
                        <option value="{{ $a->id }}" {{ old('aluno_id', $diploma->aluno_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->pessoa?->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label>
                    <select name="curso_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">—</option>
                        @foreach($cursos as $c)
                        <option value="{{ $c->id }}" {{ old('curso_id', $diploma->curso_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número de Registro</label>
                    <input type="text" name="numero_registro" value="{{ old('numero_registro', $diploma->numero_registro ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach($tipos as $val => $label)
                        <option value="{{ $val }}" {{ old('situacao', $diploma->situacao ?? 'pendente') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Solicitação</label>
                    <input type="date" name="data_solicitacao" value="{{ old('data_solicitacao', optional($diploma->data_solicitacao ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Registro</label>
                    <input type="date" name="data_registro" value="{{ old('data_registro', optional($diploma->data_registro ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Emissão</label>
                    <input type="date" name="data_emissao" value="{{ old('data_emissao', optional($diploma->data_emissao ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Colação</label>
                    <input type="date" name="data_colacao" value="{{ old('data_colacao', optional($diploma->data_colacao ?? null)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                <textarea name="observacoes" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('observacoes', $diploma->observacoes ?? '') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-cyan-500 hover:bg-cyan-400 text-white rounded-full text-sm font-bold shadow-lg shadow-cyan-500/30">{{ isset($diploma) ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('ged.diplomas.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
