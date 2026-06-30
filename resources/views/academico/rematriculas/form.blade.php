@extends('layouts.app')
@section('title', $registro ? 'Editar Rematrícula' : 'Nova Rematrícula')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">279</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $registro ? 'Editar' : 'Nova' }} Rematrícula</h1>
        </div>
        <form action="{{ $registro ? route('academico.rematriculas.update', $registro) : route('academico.rematriculas.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($registro) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Matrícula de origem <span class="text-red-500">*</span></label>
                <select name="matricula_id" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">Selecione...</option>
                    @foreach($matriculas as $m)<option value="{{ $m->id }}" {{ (string)old('matricula_id', $registro->matricula_id ?? '') === (string)$m->id ? 'selected' : '' }}>{{ $m->rotulo }}</option>@endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Futura Turma</label>
                    <select name="futura_turma_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="">—</option>
                        @foreach($turmas as $t)<option value="{{ $t->id }}" {{ (string)old('futura_turma_id', $registro->futura_turma_id ?? '') === (string)$t->id ? 'selected' : '' }}>{{ $t->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Abertura</label>
                    <input type="date" name="data_abertura" value="{{ old('data_abertura', optional($registro?->data_abertura)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach(\App\Models\Rematricula::SITUACOES as $s)<option value="{{ $s }}" {{ old('situacao', $registro->situacao ?? \App\Models\Rematricula::SITUACOES[0]) === $s ? 'selected' : '' }}>{{ $s }}</option>@endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('academico.rematriculas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
