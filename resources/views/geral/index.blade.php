@extends('layouts.app')
@section('title', 'Geral')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <i class="fa-solid fa-table-cells text-xl text-primary-500"></i>
        <h1 class="text-lg font-semibold text-gray-800">Geral</h1>
    </div>

    {{-- Cards funcionais --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('geral.questoes.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-circle-question text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Cadastro de Questões <span class="text-xs text-gray-400">(33)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['questoes'] }} questões cadastradas</div>
            </div>
        </a>
        <a href="{{ route('geral.questionarios.index') }}" class="bg-white rounded-xl border p-5 hover:shadow-md transition flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center"><i class="fa-solid fa-list-check text-xl text-primary-500"></i></div>
            <div>
                <div class="text-sm font-semibold text-gray-800">Cadastro de Questionário <span class="text-xs text-gray-400">(34)</span></div>
                <div class="text-xs text-gray-500">{{ $stats['questionarios'] }} questionários cadastrados</div>
            </div>
        </a>
    </div>

    {{-- Funções planejadas --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-600 mb-3">Outras funções do módulo</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            @foreach([['118','Questionários NPS'],['228','Questionário Avulso'],['119','Preenchimento Plano de Ensino'],['205','Preenchimento Plano de Aula'],['221','Consulta Personalizada'],['222','Cálculo de Comissões']] as $f)
            <div class="flex items-center gap-2 p-2.5 border rounded-lg text-sm text-left text-gray-400">
                <span class="font-semibold text-gray-400 min-w-[28px]">{{ $f[0] }}</span>
                <span>{{ $f[1] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
