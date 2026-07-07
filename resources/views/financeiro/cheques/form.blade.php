@extends('layouts.app')
@section('title', $cheque ? 'Editar Cheque' : 'Novo Cheque')

@section('content')
<div class="w-full">
    <div class="bg-white">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">72</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $cheque ? 'Editar Cheque' : 'Novo Cheque' }}</h1>
        </div>
        <form action="{{ $cheque ? route('financeiro.cheques.update', $cheque) : route('financeiro.cheques.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @if($cheque) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\Cheque::TIPOS as $k => $v)<option value="{{ $k }}" @selected(old('tipo', $cheque->tipo ?? 'recebido')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Número <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="{{ old('numero', $cheque->numero ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Banco</label>
                    <select name="banco_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($bancos as $b)<option value="{{ $b->id }}" @selected(old('banco_id', $cheque->banco_id ?? '')==$b->id)>{{ $b->codigo ? $b->codigo.' - ' : '' }}{{ $b->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Emitente</label>
                    <input type="text" name="emitente" value="{{ old('emitente', $cheque->emitente ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Agência</label>
                    <input type="text" name="agencia" value="{{ old('agencia', $cheque->agencia ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conta</label>
                    <input type="text" name="conta" value="{{ old('conta', $cheque->conta ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $cheque->valor ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bom para</label>
                    <input type="date" name="bom_para" value="{{ old('bom_para', optional($cheque?->bom_para)->format('Y-m-d')) }}" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação <span class="text-red-500">*</span></label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\Cheque::SITUACOES as $k => $v)<option value="{{ $k }}" @selected(old('situacao', $cheque->situacao ?? 'carteira')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Observação</label>
                <textarea name="observacao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('observacao', $cheque->observacao ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('financeiro.cheques.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
