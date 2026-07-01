@extends('layouts.app')
@section('title', $registro ? 'Editar Sub Agrupador' : 'Sub Agrupador de Cursos')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">266</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $registro ? 'Editar Sub Agrupador' : 'Sub Agrupador de Cursos' }}</h1>
        </div>
        <form action="{{ $registro ? route('ead.sub-agrupadores.update', $registro) : route('ead.sub-agrupadores.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($registro) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                <input type="text" name="nome" value="{{ old('nome', $registro->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agrupador</label>
                <select name="agrupador_curso_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($agrupadores as $a)
                    <option value="{{ $a->id }}" @selected(old('agrupador_curso_id', $registro->agrupador_curso_id ?? '') == $a->id)>{{ $a->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.sub-agrupadores.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
