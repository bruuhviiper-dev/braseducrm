@extends('layouts.app')
@section('title', 'Emissões Acadêmicas')

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-xl border p-5">
        <h1 class="text-lg font-semibold text-gray-800 mb-1">Emissões Acadêmicas</h1>
        <p class="text-sm text-gray-500">Gere os relatórios em PDF. Os que dependem de turma exigem a seleção abaixo.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- 79 Alunos Matriculados --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">79</span><h2 class="font-semibold text-gray-800">Alunos Matriculados</h2></div>
            <form method="GET" action="{{ route('academico.emissoes.alunos-matriculados') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Situação</label>
                    <select name="situacao" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Todas</option>
                        @foreach(['ativa','trancada','cancelada','concluida','transferida','evadida'] as $s)
                        <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 184 Turmas Montadas --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">184</span><h2 class="font-semibold text-gray-800">Turmas Montadas</h2></div>
            <a href="{{ route('academico.emissoes.turmas-montadas') }}" target="_blank" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</a>
        </div>

        {{-- 185 Horários Professores --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">185</span><h2 class="font-semibold text-gray-800">Horários dos Professores</h2></div>
            <a href="{{ route('academico.emissoes.horarios-professores') }}" target="_blank" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</a>
        </div>

        {{-- 60 Notas e Faltas --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">60</span><h2 class="font-semibold text-gray-800">Notas e Faltas</h2></div>
            <form method="GET" action="{{ route('academico.emissoes.notas-faltas') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                    <select name="turma_montada_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($turmasMontadas as $tm)<option value="{{ $tm->id }}">{{ $tm->nome ?? $tm->turma?->nome ?? ('TM #'.$tm->id) }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 91 Diário de Classe --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">91</span><h2 class="font-semibold text-gray-800">Diário de Classe</h2></div>
            <form method="GET" action="{{ route('academico.emissoes.diario-classe') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Turma Montada <span class="text-red-500">*</span></label>
                    <select name="turma_montada_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($turmasMontadas as $tm)<option value="{{ $tm->id }}">{{ $tm->nome ?? $tm->turma?->nome ?? ('TM #'.$tm->id) }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>
        {{-- 210 Documentos --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">210</span><h2 class="font-semibold text-gray-800">Documentos (situação de entrega)</h2></div>
            <a href="{{ route('academico.emissoes.documentos') }}" target="_blank" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar PDF</a>
        </div>
    </div>
</div>
@endsection
