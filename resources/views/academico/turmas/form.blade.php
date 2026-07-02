@extends('layouts.app')
@section('title', ($turma ?? null) ? 'Editar Turma' : 'Cadastro de Turma')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ tab: 'basicos' }">
    <div class="bg-white rounded-xl border">
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <a href="{{ route('academico.turmas.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-arrow-left"></i></a>
            <span class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded">40</span>
            <div>
                <h1 class="text-lg font-semibold text-gray-800">{{ ($turma ?? null) ? 'Editar Turma' : 'Cadastro de Turma' }}</h1>
                <p class="text-xs text-gray-400">Acadêmico › Turmas</p>
            </div>
        </div>

        {{-- Abas (fiel ao EDUQ) --}}
        <div class="border-b px-4 flex gap-1 overflow-x-auto">
            @foreach(['basicos'=>'Dados Básicos','matriculas'=>'Matrículas','montadas'=>'Turmas Montadas'] as $k => $t)
            <button type="button" @click="tab='{{ $k }}'" :class="tab==='{{ $k }}' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap">{{ $t }}</button>
            @endforeach
        </div>

        <form action="{{ ($turma ?? null) ? route('academico.turmas.update', $turma) : route('academico.turmas.store') }}" method="POST" class="p-6">
            @csrf
            @if($turma ?? null) @method('PUT') @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- ============ DADOS BÁSICOS (fiel ao EDUQ) ============ --}}
            <div x-show="tab==='basicos'" class="space-y-4">
                <label class="flex items-center gap-3 text-sm">
                    <input type="checkbox" name="finalizada" value="1" {{ old('finalizada', $turma->finalizada ?? false) ? 'checked' : '' }} class="rounded text-primary-600 w-5 h-5">
                    <span class="font-medium text-gray-700">Turma finalizada?</span>
                </label>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SIGLA <span class="text-red-500">*</span></label>
                    <input type="text" name="codigo" value="{{ old('codigo', $turma->codigo ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição <span class="text-red-500">*</span></label>
                    <input type="text" name="nome" value="{{ old('nome', $turma->nome ?? '') }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instituição de Ensino <span class="text-red-500">*</span></label>
                    <select name="instituicao_ensino_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($instituicoes as $i)<option value="{{ $i->id }}" @selected(old('instituicao_ensino_id', $turma->instituicao_ensino_id ?? '')==$i->id)>{{ $i->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Matriz Curricular <span class="text-red-500">*</span></label>
                    <select name="matriz_curricular_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($matrizes as $mz)<option value="{{ $mz->id }}" @selected(old('matriz_curricular_id', $turma->matriz_curricular_id ?? '')==$mz->id)>{{ $mz->sigla ? $mz->sigla.' - ' : '' }}{{ $mz->nome }}{{ $mz->curso ? ' ('.$mz->curso->nome.')' : '' }}</option>@endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">O curso da turma é definido pela matriz curricular selecionada.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turno <span class="text-red-500">*</span></label>
                    <select name="turno_id" required class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">Selecione...</option>
                        @foreach($turnos as $tn)<option value="{{ $tn->id }}" @selected(old('turno_id', $turma->turno_id ?? '')==$tn->id)>{{ $tn->nome }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantidade máxima de alunos</label>
                    <input type="number" name="vagas" value="{{ old('vagas', $turma->vagas ?? '') }}" min="0" class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            {{-- ============ MATRÍCULAS (read-only) ============ --}}
            <div x-show="tab==='matriculas'" x-cloak>
                @if(($turma ?? null) && $turma->matriculas->count())
                <p class="text-sm text-gray-500 mb-3">{{ $turma->matriculas->count() }} matrícula(s) nesta turma.</p>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Aluno</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Situação</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($turma->matriculas as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $m->numero_matricula ?? $m->id }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $m->aluno?->pessoa?->nome ?? '—' }}</td>
                            <td class="px-4 py-2"><span class="text-xs bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full capitalize">{{ $m->situacao }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-6 text-center">Nenhuma matrícula nesta turma ainda.</p>
                @endif
            </div>

            {{-- ============ TURMAS MONTADAS (read-only) ============ --}}
            <div x-show="tab==='montadas'" x-cloak>
                @if(($turma ?? null) && $turma->turmasMontadas->count())
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b"><tr>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Sigla</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Descrição</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">Período</th>
                        <th class="px-4 py-2 text-xs font-medium text-gray-500 uppercase"></th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($turma->turmasMontadas as $tm)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $tm->sigla ?? '—' }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800">{{ $tm->nome ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $tm->periodoLetivo?->nome ?? '—' }}</td>
                            <td class="px-4 py-2 text-right"><a href="{{ route('academico.montagem-turma.edit', $tm) }}" class="text-primary-600 hover:underline text-xs">Abrir</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-sm text-gray-400 py-6 text-center">Nenhuma turma montada para esta turma.</p>
                @endif
            </div>

            <div class="flex justify-end gap-3 pt-4 mt-4 border-t">
                <a href="{{ route('academico.turmas.index') }}" class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700"><i class="fa-solid fa-check mr-1"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection
