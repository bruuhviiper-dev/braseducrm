@extends('layouts.app')
@section('title', ($registro ? 'Editar ' : 'Novo ') . $cfg['titulo'])

@php $temAtivo = in_array('ativo', (new $cfg['model'])->getFillable()); @endphp

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">{{ $registro ? 'Editar' : 'Novo' }} — {{ $cfg['titulo'] }}</h2>
            <a href="{{ route('cadastros.index', $tipo) }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa-solid fa-arrow-left mr-1"></i>Voltar</a>
        </div>
        <form method="POST" action="{{ $registro ? route('cadastros.update', [$tipo, $registro->id]) : route('cadastros.store', $tipo) }}" class="p-6 space-y-4">
            @csrf
            @if($registro) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            @foreach($cfg['fields'] as $f)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ $f['label'] }} @if($f['required'] ?? false)<span class="text-red-500">*</span>@endif</label>
                @if($f['type'] === 'textarea')
                <textarea name="{{ $f['name'] }}" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" {{ ($f['required'] ?? false) ? 'required' : '' }}>{{ old($f['name'], $registro->{$f['name']} ?? '') }}</textarea>
                @elseif($f['type'] === 'number')
                <input type="number" step="0.01" name="{{ $f['name'] }}" value="{{ old($f['name'], $registro->{$f['name']} ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" {{ ($f['required'] ?? false) ? 'required' : '' }}>
                @else
                <input type="text" name="{{ $f['name'] }}" value="{{ old($f['name'], $registro->{$f['name']} ?? '') }}" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" {{ ($f['required'] ?? false) ? 'required' : '' }}>
                @endif
            </div>
            @endforeach

            @if($temAtivo)
            <div class="flex items-center gap-2">
                <input type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $registro->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="ativo" class="text-sm text-gray-700">Ativo</label>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ $registro ? 'Salvar Alteracoes' : 'Cadastrar' }}</button>
                <a href="{{ route('cadastros.index', $tipo) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
