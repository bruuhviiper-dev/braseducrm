@extends('layouts.app')
@section('title', 'Cadastro de Período Letivo')

@section('content')
{{-- 38 Cadastro de Período Letivo (padrão EDUQ: datas primeiro, Descrição, Descrição p/ Histórico) --}}
<div class="w-full">
    <x-eduq-header title="Cadastro de Período Letivo" breadcrumb="Acadêmico › Turmas" :back="route('academico.periodos-letivos.index')" />

    <div class="bg-white rounded-xl border p-6 pb-24">
        <form action="{{ isset($periodo) ? route('academico.periodos-letivos.update', $periodo) : route('academico.periodos-letivos.store') }}" method="POST">
            @csrf
            @isset($periodo) @method('PUT') @endisset

            <x-eduq-toggle name="ativo" :checked="old('ativo', $periodo->ativo ?? true)" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                <x-eduq-field name="data_inicio" label="Início das aulas" type="date" :required="true"
                              :value="isset($periodo) && $periodo->data_inicio ? $periodo->data_inicio->format('Y-m-d') : ''" />
                <x-eduq-field name="data_fim" label="Fim das aulas" type="date" :required="true"
                              :value="isset($periodo) && $periodo->data_fim ? $periodo->data_fim->format('Y-m-d') : ''" />
            </div>
            <x-eduq-field name="nome" label="Descrição" :value="$periodo->nome ?? ''" :required="true" />
            <x-eduq-field name="descricao_historico" label="Descrição para o Histórico" :value="$periodo->descricao_historico ?? ''" />

            <x-eduq-save />
        </form>
    </div>
</div>
@endsection
