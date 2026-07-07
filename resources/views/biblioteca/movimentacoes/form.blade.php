@extends('layouts.app')
@section('title', 'Novo Empréstimo')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">287</span>
            <h1 class="text-lg font-semibold text-gray-800">Novo Empréstimo</h1>
        </div>
        <form action="{{ route('biblioteca.movimentacoes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Exemplar disponível <span class="text-red-500">*</span></label>
                <select name="exemplar_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($exemplares as $ex)<option value="{{ $ex->id }}" {{ (string)old('exemplar_id') === (string)$ex->id ? 'selected' : '' }}>{{ ($ex->codigo ?? ('#'.$ex->id)) }} — {{ $ex->obra?->titulo }}</option>@endforeach
                </select>
                @if($exemplares->isEmpty())<p class="text-xs text-amber-600 mt-1">Nenhum exemplar disponível para empréstimo.</p>@endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)<option value="{{ $p->id }}" {{ (string)old('pessoa_id') === (string)$p->id ? 'selected' : '' }}>{{ $p->nome }}</option>@endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data do Empréstimo <span class="text-red-500">*</span></label>
                    <input type="date" name="data_emprestimo" value="{{ old('data_emprestimo', now()->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Previsão de Devolução <span class="text-red-500">*</span></label>
                    <input type="date" name="data_prevista_devolucao" value="{{ old('data_prevista_devolucao', $previsao) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Sugerida pela configuração da biblioteca.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('biblioteca.movimentacoes.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Registrar Empréstimo</button>
            </div>
        </form>
    </div>
</div>
@endsection
