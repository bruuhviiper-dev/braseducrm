@extends('layouts.app')
@section('title', $forum ? 'Editar Fórum' : 'Novo Fórum EAD')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">306</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $forum ? 'Editar Fórum' : 'Novo Fórum EAD' }}</h1>
        </div>
        <form action="{{ $forum ? route('ead.foruns.update', $forum) : route('ead.foruns.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($forum) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo', $forum->titulo ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Curso EAD</label>
                <select name="curso_ead_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($cursos as $c)
                    <option value="{{ $c->id }}" @selected(old('curso_ead_id', $forum->curso_ead_id ?? '') == $c->id)>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('ead.foruns.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
