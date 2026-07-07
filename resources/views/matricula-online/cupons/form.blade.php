@extends('layouts.app')
@section('title', isset($cupom) ? 'Editar Cupom' : 'Novo Cupom')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ isset($cupom) ? 'Editar Cupom' : 'Novo Cupom de Desconto' }}</h2>
            <a href="{{ route('matricula-online.cupons.index') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ isset($cupom) ? route('matricula-online.cupons.update', $cupom) : route('matricula-online.cupons.store') }}" class="p-6 space-y-4">
            @csrf
            @if(isset($cupom)) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Código <span class="text-red-500">*</span></label>
                <input type="text" name="codigo" value="{{ old('codigo', $cupom->codigo ?? '') }}" placeholder="Ex.: BEMVINDO10" class="w-full border rounded-lg px-3 py-2 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                    <select name="tipo" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="percentual" {{ old('tipo', $cupom->tipo ?? 'percentual') == 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                        <option value="valor" {{ old('tipo', $cupom->tipo ?? '') == 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor', $cupom->valor ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade Total</label>
                    <input type="number" min="0" name="quantidade_total" value="{{ old('quantidade_total', $cupom->quantidade_total ?? '') }}" placeholder="Ilimitado" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Validade</label>
                    <input type="date" name="validade" value="{{ old('validade', isset($cupom) && $cupom->validade ? $cupom->validade->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Aplicar o desconto sobre <span class="text-red-500">*</span></label>
                <select name="incidencia" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach(['ambas'=>'Matrícula e mensalidades','matricula'=>'Apenas a taxa de matrícula','mensalidades'=>'Apenas as mensalidades'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ old('incidencia', $cupom->incidencia ?? 'ambas') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Aplicar apenas nas mensalidades preserva o valor cheio da inscrição (base do comissionamento).</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Abertura vinculada</label>
                <select name="abertura_matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas as aberturas</option>
                    @foreach($aberturas as $a)
                    <option value="{{ $a->id }}" {{ old('abertura_matricula_id', $cupom->abertura_matricula_id ?? '') == $a->id ? 'selected' : '' }}>{{ $a->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Exclusivo do consultor</label>
                <select name="consultor_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Qualquer consultor</option>
                    @foreach($consultores as $c)
                    <option value="{{ $c->id }}" {{ old('consultor_id', $cupom->consultor_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $cupom->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                    {{ isset($cupom) ? 'Salvar Alteracoes' : 'Cadastrar' }}
                </button>
                <a href="{{ route('matricula-online.cupons.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
