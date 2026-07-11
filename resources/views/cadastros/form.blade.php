@extends('layouts.app')
@section('title', $cfg['titulo'])

@php $temAtivo = in_array('ativo', (new $cfg['model'])->getFillable()); @endphp

@section('content')
{{-- Cadastro genérico no padrão EDUQ Clean UI: Ativo toggle no topo, floating labels, FAB Salvar --}}
<div class="w-full">
    <x-eduq-header
        :title="(isset($cfg['codigo']) ? $cfg['codigo'] . ' ' : '') . $cfg['titulo']"
        :breadcrumb="$cfg['breadcrumb'] ?? 'Acadêmico › Cadastros Essenciais'"
        :back="route('cadastros.index', $tipo)" />

    <div class="bg-white rounded-xl border p-6 pb-24">
        <form method="POST" action="{{ $registro ? route('cadastros.update', [$tipo, $registro->id]) : route('cadastros.store', $tipo) }}">
            @csrf
            @if($registro) @method('PUT') @endif

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            @if($temAtivo)
            <x-eduq-toggle name="ativo" :checked="old('ativo', $registro->ativo ?? true)" />
            @endif

            @foreach($cfg['fields'] as $f)
                @php $val = old($f['name'], $registro->{$f['name']} ?? ''); @endphp
                @if($f['name'] === 'ativo')
                    @continue
                @elseif($f['type'] === 'boolean')
                    {{-- toggle EDUQ para campos booleanos --}}
                    <label class="flex items-center justify-between w-full border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer mb-3">
                        <span class="text-sm text-gray-700">{{ $f['label'] }}</span>
                        <input type="hidden" name="{{ $f['name'] }}" value="0">
                        <input type="checkbox" name="{{ $f['name'] }}" value="1" @checked(old($f['name'], $registro->{$f['name']} ?? false)) class="sr-only peer">
                        <span class="w-11 h-6 rounded-full bg-gray-300 peer-checked:bg-cyan-500 relative transition after:content-[''] after:absolute after:w-5 after:h-5 after:bg-white after:rounded-full after:top-0.5 after:left-0.5 peer-checked:after:translate-x-5 after:transition after:shadow"></span>
                    </label>
                @elseif($f['type'] === 'select')
                    <x-eduq-field :name="$f['name']" :label="$f['label']" type="select" :required="$f['required'] ?? false"
                                  :options="$f['options'] ?? []" :selected="$val" />
                @elseif($f['type'] === 'date')
                    <x-eduq-field :name="$f['name']" :label="$f['label']" type="date" :required="$f['required'] ?? false"
                                  :value="isset($registro) && $registro?->{$f['name']} ? \Illuminate\Support\Carbon::parse($registro->{$f['name']})->format('Y-m-d') : ''" />
                @elseif($f['type'] === 'number')
                    <x-eduq-field :name="$f['name']" :label="$f['label']" type="number" :required="$f['required'] ?? false" :value="$val" />
                @elseif($f['type'] === 'textarea')
                    <x-eduq-field :name="$f['name']" :label="$f['label']" type="textarea" :required="$f['required'] ?? false" :value="$val" />
                @else
                    <x-eduq-field :name="$f['name']" :label="$f['label']" :required="$f['required'] ?? false" :value="$val" />
                @endif
            @endforeach

            <x-eduq-save />
        </form>
    </div>
</div>
@endsection
