@extends('layouts.app')
@section('title', ($requerimento ?? null) ? 'Editar Requerimento' : 'Manutenção de Requerimentos')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ tab: 'basicos', vinculo: '{{ old('vinculo_tipo', $requerimento->vinculo_tipo ?? 'matricula') }}' }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('requerimentos.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">96</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Manutenção de Requerimentos</h1>
                <p class="text-xs text-gray-400">Acadêmico › Requerimentos</p>
            </div>
        </div>

        {{-- Abas (fiel ao EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','anotacoes'=>'Anotações'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form method="POST" action="{{ ($requerimento ?? null) ? route('requerimentos.update', $requerimento) : route('requerimentos.store') }}" class="p-6">
            @csrf
            @if($requerimento ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS BÁSICOS (fiel ao EDUQ) ============ --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                {{-- Radio: Pessoa / Matrícula / Matrícula EAD --}}
                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="vinculo_tipo" value="pessoa" x-model="vinculo" class="text-primary-600"> Pessoa</label>
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="vinculo_tipo" value="matricula" x-model="vinculo" class="text-primary-600"> Matrícula</label>
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="vinculo_tipo" value="matricula_ead" x-model="vinculo" class="text-primary-600"> Matrícula EAD</label>
                </div>

                {{-- Pessoa --}}
                <div x-show="vinculo==='pessoa'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pessoa <span class="text-red-500">*</span></label>
                    <select name="pessoa_id" class="w-full border rounded-lg px-3 py-2 text-sm" x-bind:required="vinculo==='pessoa'">
                        <option value="">Selecione...</option>
                        @foreach($pessoas as $p)<option value="{{ $p->id }}" @selected(old('pessoa_id', $requerimento->pessoa_id ?? '')==$p->id)>{{ $p->nome }}</option>@endforeach
                    </select>
                </div>

                {{-- Matrícula --}}
                <div x-show="vinculo==='matricula'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matrícula <span class="text-red-500">*</span></label>
                    <select name="matricula_id" class="w-full border rounded-lg px-3 py-2 text-sm" x-bind:required="vinculo==='matricula'">
                        <option value="">Selecione...</option>
                        @foreach($matriculas as $m)<option value="{{ $m->id }}" @selected(old('matricula_id', $requerimento->matricula_id ?? '')==$m->id)>{{ $m->numero_matricula ?? ('#'.$m->id) }} — {{ $m->aluno?->pessoa?->nome ?? 'Aluno '.$m->aluno_id }}</option>@endforeach
                    </select>
                </div>

                {{-- Matrícula EAD --}}
                <div x-show="vinculo==='matricula_ead'" x-cloak>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matrícula EAD <span class="text-red-500">*</span></label>
                    <select name="matricula_ead_id" class="w-full border rounded-lg px-3 py-2 text-sm" x-bind:required="vinculo==='matricula_ead'">
                        <option value="">Selecione...</option>
                        @foreach($matriculasEad as $me)<option value="{{ $me->id }}" @selected(old('matricula_ead_id', $requerimento->matricula_ead_id ?? '')==$me->id)>#{{ $me->id }} — {{ $me->aluno?->pessoa?->nome ?? 'Aluno '.$me->aluno_id }}</option>@endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Requerimento <span class="text-red-500">*</span></label>
                    <select name="tipo_requerimento_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($tipos as $t)<option value="{{ $t->id }}" @selected(old('tipo_requerimento_id', $requerimento->tipo_requerimento_id ?? '')==$t->id)>{{ $t->nome }}</option>@endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Situação</label>
                    <select name="situacao" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['pendente'=>'Aguardando Autorização','aprovado'=>'Em Andamento','entregue'=>'Concluído','reprovado'=>'Reprovado','cancelado'=>'Cancelado'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('situacao', $requerimento->situacao ?? 'pendente')===$val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="descricao" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('descricao', $requerimento->descricao ?? '') }}</textarea>
                </div>
            </div>

            {{-- ============ ANOTAÇÕES ============ --}}
            <div x-show="tab==='anotacoes'" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anotações</label>
                <textarea name="anotacoes" rows="8" class="w-full border rounded-lg px-3 py-2 text-sm">{{ old('anotacoes', $requerimento->anotacoes ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('requerimentos.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
