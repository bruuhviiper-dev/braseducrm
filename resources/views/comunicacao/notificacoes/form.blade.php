@extends('layouts.app')
@section('title', 'Nova Notificação')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">260</span>
            <h1 class="text-lg font-semibold text-gray-800">Nova Notificação ao Aluno</h1>
        </div>
        <form action="{{ route('comunicacao.notificacoes.store') }}" method="POST" class="p-6 space-y-4" x-data="{ todos: false }">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mensagem</label>
                <textarea name="mensagem" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('mensagem') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\NotificacaoAluno::TIPOS as $k => $v)<option value="{{ $k }}" @selected(old('tipo')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aluno</label>
                    <select name="aluno_id" :disabled="todos" class="w-full border rounded-lg px-3 py-2 text-sm disabled:bg-gray-100">
                        <option value="">Selecione...</option>
                        @foreach($alunos as $a)<option value="{{ $a->id }}" @selected(old('aluno_id')==$a->id)>{{ $a->pessoa?->nome ?? ('Aluno #'.$a->id) }}</option>@endforeach
                    </select>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="para_todos" value="1" x-model="todos" class="rounded border-gray-300 text-primary-600"> Enviar para todos os alunos
            </label>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('comunicacao.notificacoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-paper-plane mr-1"></i> Enviar</button>
            </div>
        </form>
    </div>
</div>
@endsection
