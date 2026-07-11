@extends('layouts.app')
@section('title', isset($sala) ? 'Cadastro de Sala' : 'Cadastro de Sala')

@section('content')
{{-- 39 Cadastro de Sala (padrão EDUQ Clean UI: Ativo toggle no topo, floating labels, FAB Salvar) --}}
<div class="w-full">
    <x-eduq-header title="Cadastro de Sala" breadcrumb="Acadêmico › Cadastros Essenciais" :back="route('academico.salas.index')" />

    <div class="bg-white rounded-xl border p-6 pb-24">
        <form action="{{ isset($sala) ? route('academico.salas.update', $sala) : route('academico.salas.store') }}" method="POST">
            @csrf
            @isset($sala) @method('PUT') @endisset

            <x-eduq-toggle name="ativo" :checked="old('ativo', $sala->ativo ?? true)" />
            <x-eduq-field name="sigla" label="SIGLA" :value="$sala->sigla ?? ''" :required="true" />
            <x-eduq-field name="nome" label="Descrição" :value="$sala->nome ?? ''" :required="true" />
            {{-- Capacidade máxima: trava novas matrículas se a turma lotar a sala (doc revisão) --}}
            <x-eduq-field name="capacidade" label="Capacidade Máxima" type="number" :value="$sala->capacidade ?? ''" />

            <x-eduq-save />
        </form>
    </div>
</div>
@endsection
