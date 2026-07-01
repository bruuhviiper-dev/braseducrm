@extends('layouts.app')
@section('title', $consulta ? 'Editar Consulta' : 'Nova Consulta Personalizada')

@section('content')
@php
    $registro = collect($entidades)->map(fn($e) => ['label' => $e['label'], 'campos' => $e['campos']]);
@endphp
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">221</span>
            <h1 class="text-lg font-semibold text-gray-800">{{ $consulta ? 'Editar Consulta' : 'Nova Consulta Personalizada' }}</h1>
        </div>
        <form action="{{ $consulta ? route('geral.consultas.update', $consulta) : route('geral.consultas.store') }}" method="POST" class="p-6 space-y-4"
              x-data="consultaForm(@js($registro), '{{ old('entidade', $consulta->entidade ?? '') }}', @js(old('campos', $consulta->campos ?? [])), '{{ old('filtro_campo', $consulta->filtro_campo ?? '') }}')">
            @csrf
            @if($consulta) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome da consulta <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $consulta->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Entidade <span class="text-red-500">*</span></label>
                    <select name="entidade" x-model="entidade" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($entidades as $k => $e)<option value="{{ $k }}">{{ $e['label'] }}</option>@endforeach
                    </select>
                </div>
            </div>

            <div x-show="entidade">
                <label class="block text-sm font-medium text-gray-700 mb-2">Campos a exibir</label>
                <div class="flex flex-wrap gap-3">
                    <template x-for="(label, campo) in camposDisponiveis()" :key="campo">
                        <label class="flex items-center gap-2 text-sm border rounded-lg px-3 py-1.5">
                            <input type="checkbox" name="campos[]" :value="campo" x-model="campos" class="rounded border-gray-300 text-primary-600">
                            <span x-text="label"></span>
                        </label>
                    </template>
                </div>
            </div>

            <div x-show="entidade" class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtro (opcional)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <select name="filtro_campo" x-model="filtroCampo" class="border rounded-lg px-3 py-2 text-sm">
                        <option value="">— campo —</option>
                        <template x-for="(label, campo) in camposDisponiveis()" :key="campo">
                            <option :value="campo" x-text="label"></option>
                        </template>
                    </select>
                    <select name="filtro_operador" class="border rounded-lg px-3 py-2 text-sm">
                        @foreach(\App\Models\ConsultaPersonalizada::OPERADORES as $k => $v)<option value="{{ $k }}" @selected(old('filtro_operador', $consulta->filtro_operador ?? '')==$k)>{{ $v }}</option>@endforeach
                    </select>
                    <input type="text" name="filtro_valor" value="{{ old('filtro_valor', $consulta->filtro_valor ?? '') }}" placeholder="Valor" class="border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('geral.consultas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
function consultaForm(registro, entidadeIni, camposIni, filtroCampoIni) {
    return {
        registro,
        entidade: entidadeIni || '',
        campos: camposIni || [],
        filtroCampo: filtroCampoIni || '',
        camposDisponiveis() {
            return this.entidade && this.registro[this.entidade] ? this.registro[this.entidade].campos : {};
        },
    };
}
</script>
@endsection
