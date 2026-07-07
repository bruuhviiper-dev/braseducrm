@extends('layouts.app')
@section('title', 'Nova Reserva')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">289</span>
            <h1 class="text-lg font-semibold text-gray-800">Reserva de Exemplares</h1>
        </div>
        <form action="{{ route('biblioteca.reservas.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biblioteca <span class="text-red-500">*</span></label>
                <select name="biblioteca_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($bibliotecas as $b)<option value="{{ $b->id }}" {{ (string)old('biblioteca_id') === (string)$b->id ? 'selected' : '' }}>{{ $b->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Obra <span class="text-red-500">*</span></label>
                <select name="obra_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($obras as $o)<option value="{{ $o->id }}" {{ (string)old('obra_id') === (string)$o->id ? 'selected' : '' }}>{{ $o->titulo }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                <select name="pessoa_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">Selecione...</option>
                    @foreach($pessoas as $p)<option value="{{ $p->id }}" {{ (string)old('pessoa_id') === (string)$p->id ? 'selected' : '' }}>{{ $p->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data da Reserva <span class="text-red-500">*</span></label>
                <input type="date" name="data_reserva" value="{{ old('data_reserva', now()->format('Y-m-d')) }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('biblioteca.reservas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
