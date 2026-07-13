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
            <p class="text-xs text-gray-500 mb-3">Construtor de relatório: Layouts salvos, escolha de colunas, filtros e export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.alunos-matriculados') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
        </div>

        {{-- 184 Turmas Montadas --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">184</span><h2 class="font-semibold text-gray-800">Turmas Montadas</h2></div>
            <p class="text-xs text-gray-500 mb-3">Construtor de relatório: Layouts salvos, escolha de colunas, filtros e export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.turmas-montadas') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
        </div>

        {{-- 185 Horários Professores --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">185</span><h2 class="font-semibold text-gray-800">Horários dos Professores</h2></div>
            <p class="text-xs text-gray-500 mb-3">Construtor: Professores/Turmas/Período + Layouts salvos + export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.horarios-professores') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
        </div>

        {{-- 60 Notas e Faltas --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">60</span><h2 class="font-semibold text-gray-800">Notas e Faltas</h2></div>
            <p class="text-xs text-gray-500 mb-3">Construtor: Turma Montada, Disciplinas, Situação, Alunos e "Incluir notas das avaliações?" + export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.notas-faltas') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
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

        {{-- 27 Matriz Curricular --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">27</span><h2 class="font-semibold text-gray-800">Matriz Curricular</h2></div>
            <form method="GET" action="{{ route('academico.emissoes.matriz-curricular') }}" target="_blank" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="block text-xs text-gray-500 mb-1">Matriz <span class="text-red-500">*</span></label>
                    <select name="matriz_curricular_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($matrizes as $mz)<option value="{{ $mz->id }}">{{ ($mz->curso?->nome ? $mz->curso->nome.' — ' : '').$mz->nome }}</option>@endforeach
                    </select>
                </div>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> PDF</button>
            </form>
        </div>

        {{-- 305 Disciplinas dos Alunos --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">305</span><h2 class="font-semibold text-gray-800">Disciplinas dos Alunos</h2></div>
            <p class="text-xs text-gray-500 mb-3">Construtor: Turma Montada/Disciplinas/Situações/Tipos + Layouts + export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.disciplinas-alunos') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
        </div>

        {{-- 249 Pendências de Notas e Faltas --}}
        <div class="bg-white rounded-xl border p-5">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">249</span><h2 class="font-semibold text-gray-800">Pendências de Notas e Faltas</h2></div>
            <p class="text-xs text-gray-500 mb-3">Construtor: Turma Montada/Professor/Período, Notas e Frequências pendentes + Layouts + export PDF/CSV/XLSX.</p>
            <a href="{{ route('academico.emissoes.pendencias-notas-faltas') }}" class="inline-block px-4 py-2 bg-cyan-500 text-white rounded-lg text-sm font-medium hover:bg-cyan-600"><i class="fa-solid fa-sliders mr-1"></i> Abrir construtor</a>
        </div>

        {{-- 114 Declaração de Aula Ministrada --}}
        <div class="bg-white rounded-xl border p-5 md:col-span-2">
            <div class="flex items-center gap-2 mb-3"><span class="text-xs font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">114</span><h2 class="font-semibold text-gray-800">Declaração de Aula Ministrada</h2></div>
            <form method="GET" action="{{ route('academico.emissoes.declaracao-aula') }}" target="_blank" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">Professor <span class="text-red-500">*</span></label>
                    <select name="profissional_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($profissionais as $p)<option value="{{ $p->id }}">{{ $p->pessoa?->nome ?? ('Prof. #'.$p->id) }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Disciplina <span class="text-red-500">*</span></label>
                    <select name="disciplina_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($disciplinas as $d)<option value="{{ $d->id }}">{{ $d->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Início <span class="text-red-500">*</span></label>
                    <input type="date" name="data_inicio" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Fim <span class="text-red-500">*</span></label>
                    <input type="date" name="data_fim" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-5">
                    <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700"><i class="fa-solid fa-file-pdf mr-1"></i> Gerar Declaração (PDF)</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
