@extends('layouts.app')
@section('title', 'Emissões EAD')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <h1 class="text-lg font-semibold text-gray-800 mb-1">Emissões EAD</h1>
        <p class="text-sm text-gray-500">Gere os relatórios em PDF dos alunos matriculados nos cursos EAD.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- 174 Alunos Matriculados EAD --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">174</span><h2 class="font-semibold text-gray-800">Alunos Matriculados EAD</h2></div>
            <form method="GET" action="{{ route('ead.emissoes.alunos-matriculados') }}" target="_blank" class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Curso EAD</label>
                    <select name="curso_ead_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        @foreach($cursos as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Situação</label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach(['ativa','concluida','cancelada','trancada'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</button>
            </form>
        </div>

        {{-- 219 Notas dos Alunos EAD --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">219</span><h2 class="font-semibold text-gray-800">Notas dos Alunos EAD</h2></div>
            <form method="GET" action="{{ route('ead.emissoes.notas-alunos') }}" target="_blank" class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Curso EAD</label>
                    <select name="curso_ead_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todos</option>
                        @foreach($cursos as $c)<option value="{{ $c->id }}">{{ $c->nome }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</button>
            </form>
        </div>
    </div>
</div>
@endsection
